# -*- coding: utf-8 -*-
from odoo import models, fields, api
from .hikvision_api import HikvisionAPI

class HikvisionDevice(models.Model):
    _name = 'hikvision.device'
    _description = 'Dispositivo Hikvision'

    ip = fields.Char(string='IP')
    name = fields.Char(string='Nombre')
    model = fields.Char(string='Modelo')
    other_info = fields.Text(string='Información adicional')

    @api.model
    def search_and_update_devices(self):
        """
        Busca dispositivos Hikvision en la red y actualiza los registros en el modelo.
        """
        devices = HikvisionAPI.search_devices()
        self.search([]).unlink()  # Eliminar registros antiguos
        for device in devices:
            other_attrs = {k: v for k, v in device.items() if k not in ['ip', 'name', 'model']}
            self.create({
                'ip': device.get('ip', ''),
                'name': device.get('name', ''),
                'model': device.get('model', ''),
                'other_info': str(other_attrs) if other_attrs else '',
            })
