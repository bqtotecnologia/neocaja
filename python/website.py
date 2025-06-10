import requests
import certifi

from lxml import html
from flask import Flask, jsonify

from config import config

BCV_URL = 'https://www.bcv.org.ve/'
#SSL_PATH = '/home/atlantox/Projects/neocaja/python/venv/lib/python3.12/site-packages/certifi/cacert.pem'
SSL_PATH = '/home/atlantox/Projects/neocaja/python/cacert.pem'

USD_XPATH = "//*[@id='dolar']//strong"
EURO_XPATH = "//*[@id='euro']//strong"

def create_app():
    print(certifi.where())
    app = Flask(__name__)
    app.config.from_object(config['development'])

    def NotFound(error):
        return jsonify({'success': False, 'message': 'Ruta no encontrada'}), 404
    
    @app.route('/')
    def NeocajaWorking():
        return 'Neocaja API working!'

    # Getting the USD value
    @app.route('/usd')
    def GetUsdValue():
        try:
            response = requests.get(BCV_URL, verify=(SSL_PATH))
        except Exception as e:            
            return jsonify({'success': False, 'message': str(e)})        
        
        if response.status_code != 200:
            message = 'La web "{0}" retornó un código de respuesta distinto a 200: {1}'.format(BCV_URL, response.status_code)
            return jsonify({'success': False, 'message': message})
    
        tree = html.fromstring(response.content)
        elements = tree.xpath(USD_XPATH)
        for element in elements:
            value = FloatConvert(element.text)

        return jsonify({'success': True, 'value': value})
    
    # Getting the Euro value
    @app.route('/euro')
    def GetEuroValue():
        try:
            response = requests.get(BCV_URL)
        except Exception as e:            
            return jsonify({'success': False, 'message': str(e)})        
        
        if response.status_code != 200:
            message = 'La web "{0}" retornó un código de respuesta distinto a 200: {1}'.format(BCV_URL, response.status_code)
            return jsonify({'success': False, 'message': message})
    
        tree = html.fromstring(response.content)
        elements = tree.xpath(EURO_XPATH)
        for element in elements:
            value = FloatConvert(element.text)
            

        return jsonify({'success': True, 'value': value})

    def FloatConvert(value:str):
        stripped = value.strip().replace(',', '.')
        splits = stripped.split('.')
        intPart = splits[0]
        floatPart = '0'

        if len(splits) > 1:
            floatPart = splits[1]

        floatPart = floatPart[0:4]

        return f'{intPart}.{floatPart}'

        


    app.register_error_handler(404, NotFound)

    return app