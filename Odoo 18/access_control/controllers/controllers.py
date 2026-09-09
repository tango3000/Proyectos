# -*- coding: utf-8 -*-
from odoo import http
from odoo.http import request
from odoo.exceptions import AccessDenied
from odoo.tools import config
from odoo import _
from odoo.http import Response
import json
from ..models.hikvision_api import HikvisionAPI

class HikvisionController(http.Controller):

    @http.route('/access_control/hikvision/search_devices', type='json', auth='user')
    def search_devices(self):
        """
        Endpoint para buscar dispositivos Hikvision en la red y devolver la lista.
        Solo accesible para usuarios autenticados.
        """
        try:
            devices = HikvisionAPI.search_devices()
            return {'status': 'success', 'devices': devices}
        except Exception as e:
            return {'status': 'error', 'message': str(e)}
