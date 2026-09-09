import asyncio
from bleak import BleakClient

    print(f"Datos recibidos de {sender}: {data}")

async def main():
    device_name = "Yoda0"
    print(f"Intentando conectar a {device_name}. Activa la balanza cuando veas 'Intentando conectar...'")
    while True:
        try:
            async with BleakClient(device_name) as client:
                print("¡Conectado!")
                # Intentar emparejar si es necesario
                await client.pair()
                services = await client.get_services()
                for service in services:
                    print(f"Servicio: {service.uuid}")
                    for char in service.characteristics:
                        print(f"  Característica: {char.uuid}, Propiedades: {char.properties}")
                        if "notify" in char.properties:
                            print(f"    Suscribiéndose a notificaciones en {char.uuid}")
                            await client.start_notify(char.uuid, notification_handler)
                print("Esperando datos... Presiona Ctrl+C para salir.")
                await asyncio.sleep(300)  # Esperar 5 minutos por datos
                break  # Salir del loop si se conectó
        except Exception as e:
            print(f"Error al conectar: {e}. Reintentando en 2 segundos...")
            await asyncio.sleep(2)

asyncio.run(main())
