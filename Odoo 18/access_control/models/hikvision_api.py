# -*- coding: utf-8 -*-
import socket
import threading
import time
import requests
from xml.etree import ElementTree


class HikvisionAPI:
    """
    Clase para descubrir dispositivos Hikvision en la red local usando broadcast UDP
    y luego consultar detalles vía HTTP en /ISAPI/ContentMgmt/InputProxy/search.
    """

    DISCOVERY_PORT = 37020
    DISCOVERY_MESSAGE = b'ISAPI/ContentMgmt/InputProxy/search'  # Mensaje de descubrimiento (ejemplo)
    DISCOVERY_TIMEOUT = 5  # segundos para esperar respuestas

    @staticmethod
    def discover_devices():
        """
        Envía un broadcast UDP para descubrir dispositivos Hikvision en la red local.
        Recoge las IPs que responden.
        """
        devices_ips = set()

        def listen_for_responses(sock):
            sock.settimeout(HikvisionAPI.DISCOVERY_TIMEOUT)
            start_time = time.time()
            while time.time() - start_time < HikvisionAPI.DISCOVERY_TIMEOUT:
                try:
                    data, addr = sock.recvfrom(1024)
                    if data:
                        devices_ips.add(addr[0])
                except socket.timeout:
                    break
                except Exception:
                    break

        # Crear socket UDP para broadcast
        sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        sock.setsockopt(socket.SOL_SOCKET, socket.SO_BROADCAST, 1)
        sock.bind(('', 0))

        listener_thread = threading.Thread(target=listen_for_responses, args=(sock,))
        listener_thread.start()

        # Enviar mensaje broadcast
        broadcast_address = ('<broadcast>', HikvisionAPI.DISCOVERY_PORT)
        sock.sendto(HikvisionAPI.DISCOVERY_MESSAGE, broadcast_address)

        listener_thread.join()
        sock.close()

        return list(devices_ips)

    @staticmethod
    def get_device_info(ip):
        """
        Consulta HTTP al dispositivo Hikvision para obtener detalles.
        """
        url = f"http://{ip}/ISAPI/ContentMgmt/InputProxy/search"
        try:
            response = requests.get(url, timeout=3)
            response.raise_for_status()
            return HikvisionAPI.parse_response(response.content)
        except requests.RequestException:
            return None

    @staticmethod
    def parse_response(xml_data):
        """
        Parsea la respuesta XML de la API y extrae información relevante de los dispositivos.
        """
        devices = []
        try:
            root = ElementTree.fromstring(xml_data)
            for search_result in root.findall('.//SearchResult'):
                device_info = {}
                ip = search_result.find('IPAddress')
                name = search_result.find('DeviceName')
                model = search_result.find('Model')
                if ip is not None:
                    device_info['ip'] = ip.text
                if name is not None:
                    device_info['name'] = name.text
                if model is not None:
                    device_info['model'] = model.text
                for child in search_result:
                    tag = child.tag
                    if tag not in ['IPAddress', 'DeviceName', 'Model']:
                        device_info[tag] = child.text
                devices.append(device_info)
        except ElementTree.ParseError:
            return []
        return devices

    @staticmethod
    def search_devices():
        """
        Descubre dispositivos y obtiene detalles de cada uno.
        """
        ips = HikvisionAPI.discover_devices()
        all_devices = []
        for ip in ips:
            info = HikvisionAPI.get_device_info(ip)
            if info:
                all_devices.extend(info)
        return all_devices
