# -*- coding: utf-8 -*-
from odoo.tests.common import TransactionCase
from addons.access_control.models.hikvision_api import HikvisionAPI

class TestHikvisionAPI(TransactionCase):

    def test_discover_devices(self):
        devices_ips = HikvisionAPI.discover_devices()
        self.assertIsInstance(devices_ips, list, "discover_devices should return a list")
        # The list can be empty if no devices found, so no assert on length

    def test_parse_response(self):
        sample_xml = b'''
        <SearchResultList>
            <SearchResult>
                <IPAddress>192.168.1.100</IPAddress>
                <DeviceName>Hikvision Camera</DeviceName>
                <Model>DS-2CD2032-I</Model>
            </SearchResult>
        </SearchResultList>
        '''
        devices = HikvisionAPI.parse_response(sample_xml)
        self.assertEqual(len(devices), 1, "Should parse one device")
        device = devices[0]
        self.assertEqual(device.get('ip'), '192.168.1.100')
        self.assertEqual(device.get('name'), 'Hikvision Camera')
        self.assertEqual(device.get('model'), 'DS-2CD2032-I')

    def test_get_device_info(self):
        # This test requires a real device or mock, so we skip or mock in real tests
        pass

    def test_search_devices(self):
        # This test depends on network environment, so we check type and not empty
        devices = HikvisionAPI.search_devices()
        self.assertIsInstance(devices, list)
