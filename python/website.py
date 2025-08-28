from flask import Flask, jsonify
from config import config

import requests
import certifi
from bs4 import BeautifulSoup
from lxml import etree
import urllib3
import urllib3.contrib.pyopenssl
import ssl
    

USD_XPATH = "//*[@id='dolar']//strong"
EUR_XPATH = "//*[@id='euro']//strong"

def create_app():   
    app = Flask(__name__)
    app.config.from_object(config['development'])

    def NotFound(error):
        return jsonify({'success': False, 'message': 'Ruta no encontrada'}), 404
    
    # To know if the API are working
    @app.route('/')
    def NeocajaWorking():
        return 'Neocaja API working!'   
    

    # Extracting data directly from www.bcv.org
    
    @app.route('/usd')
    def GetUsdValue():
        return GetValueDirectlyFromBCV(USD_XPATH)
    
    
    @app.route('/eur')
    def GetEurValue():
        return GetValueDirectlyFromBCV(EUR_XPATH)
    
    
    # Getting VES value (always 1.000)
    @app.route('/ves')
    def GetVesValue():        
        return jsonify({'success':True, 'result': '1.0000'}), 200    
    
    
    def FloatConvert(value:str):
        stripped = value.strip().replace(',', '.')
        splits = stripped.split('.')
        intPart = splits[0]
        floatPart = '0'

        if len(splits) > 1:
            floatPart = splits[1]

        floatPart = floatPart[0:4]
        while len(floatPart) < 4:
            floatPart += '0'

        return f'{intPart}.{floatPart}'

    def GetValueDirectlyFromBCV(xpath):
        success = False
        urllib3.contrib.pyopenssl.inject_into_urllib3()
        #http = urllib3.PoolManager(
            #cert_reqs='CERT_REQUIRED', # Force certificate check.
            #ca_certs=certifi.where(), # Path to the Certifi bundle.
            #)
        
        #response = http.request('GET', 'https://www.bcv.org.ve/glosario/cambio-oficial')
        #response = urllib3.request("GET", "https://www.bcv.org.ve/glosario/cambio-oficial")

        response = requests.get('https://www.bcv.org.ve/glosario/cambio-oficial', verify=certifi.where()) # production
        # For local testing comment the previous line and uncomment the next one
        #response = requests.get('https://www.bcv.org.ve/glosario/cambio-oficial', verify=False) # development
        soup = BeautifulSoup(response.data, 'lxml')
        tree = etree.fromstring(str(soup), parser=etree.HTMLParser())
        target_element = tree.xpath(xpath)

        result = 'Vacío'
        try:
            if target_element:
                text_content = target_element[0].text
                result = FloatConvert(text_content)
                success = True
        except Exception:
            result = 'XPATH erróneo. Contacte al personal de tecnología'
            
        result = {'success': success, 'result': result}
        return jsonify(result), 200    

    app.register_error_handler(404, NotFound)

    return app


    '''
    ###################################################################################
    # Getting data from PyDolarVenezuela API
    @app.route('/usd')
    def GetUsdValue():
        response = Pydolarve('usd')
        return jsonify(response), 200
    
    @app.route('/eur')
    def GetEuroValue():
        response = Pydolarve('eur')
        return jsonify(response), 200

    def Pydolarve(coin):
        state = True
        params = {
            "currency": coin,
            "rounded_price": False
        }
        try:
            response = requests.get("https://pydolarve.org/api/v2/tipo-cambio", params=params)        
            json = response.json()
            result = json['price']
            result = FloatConvert(str(result))

        except Exception as e:
            result = str(e)
            state = False

        return {'success': state, 'result': result}
    '''