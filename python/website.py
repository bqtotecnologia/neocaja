import requests
from flask import Flask, jsonify
from config import config

def create_app():   
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
        response = Pydolarve('usd')
        return jsonify(response), 200
    
    # Getting the Euro value
    @app.route('/eur')
    def GetEuroValue():
        response = Pydolarve('eur')
        return jsonify(response), 200
    
    # Getting the Ves value
    @app.route('/ves')
    def GetVesValue():        
        return jsonify({'success':True, 'result': '1.0000'}), 200
    
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

    app.register_error_handler(404, NotFound)

    return app
