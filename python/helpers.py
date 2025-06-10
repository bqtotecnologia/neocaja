import re 
from datetime import datetime

DATES_LENGTH_CONFIG = {
    'initial_date': {'min': 8, 'max': 10},
    'final_date': {'min': 8, 'max': 10}
}

def ValidateLength(validations, data):
    lengthOK = True
    for field, value in validations.items():
        if field in data:
            fieldLength = len(data[field])
            if fieldLength > value['max'] or fieldLength < value['min']:
                lengthOK = 'El campo {0} incumple el requisito mínimo o máximo de caracteres permitido'.format(field)
                break
    
    return lengthOK


def HasSuspiciousCharacters(indexes, content):
    '''
    Recieves a list of indexes and a dict
    Return True if at least one field has suspiciosu characters
    '''
    suspiciousFound = False
    for index in indexes:
        suspiciousRegex = r'[(){}[\]\\¡!¿?=\-<>|&\'"]'
        if re.findall(suspiciousRegex, content[index]):
            suspiciousFound = 'El campo {0} contiene caracteres sospechosos'.format(index)
            break

    return suspiciousFound

    
def HasEmptyFields(indexes, content, exactData = True):
    '''
    Recieves a list of indexes and a dict
    Check if at least one index is empty or not exists
    Return a dict with the requried indexes and their content

    If exactData is True, not founded indexes will be ignored
    '''
    error = ''
    finalData = {}
    for index in indexes:
        if index not in content and exactData:
            error = '{0} No encontrado'.format(index)
            break

        if index in content:
            if type(content[index]) is str:
                finalData[index] = content[index].strip()
            else:
                finalData[index] = content[index]

    return finalData if error == '' else error


def EmailIsOK(email):
    suspiciousRegex = r'[(){}[\]\\¡!¿?=<>|&\'"]'
    emailRegex = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    emailOK = False
    if re.findall(emailRegex, email) and not re.findall(suspiciousRegex, email):
        emailOK = True

    return emailOK


def StringToDatetime(strDate):
    result = False

    try:
        year, month, day = strDate.split('-')
        deliverDate = datetime(int(year), int(month), int(day))
        result = deliverDate 
    except:
        pass

    return result


def ValidateInitialAndFinalDate(data):
    error = ''

    cleanData = HasEmptyFields(['initial_date', 'final_date'], data)
    if type(cleanData) is str:
        error = cleanData

    if error == '':
        lengthOK = ValidateLength(DATES_LENGTH_CONFIG, cleanData)
        if lengthOK is not True:
            error = lengthOK

    if error == '':
        if StringToDatetime(cleanData['initial_date']) is False:
            error = 'Fecha de inicio inválida'

        if StringToDatetime(cleanData['final_date']) is False:
            error = 'Fecha de fin inválida'
    
    if error == '':
        datesOk = ValidateDateRange(cleanData['initial_date'], cleanData['final_date'])
        if datesOk is False:
            error = 'La fecha de inicio debe ser más temprana que la de fin'
    
    return cleanData if error == '' else error


def ValidateDateRange(initialDate, finalDate):
    result = True
    try:
        year, month, day = initialDate.split('-')
        initial = datetime(int(year), int(month), int(day))

        year, month, day = finalDate.split('-')
        final = datetime(int(year), int(month), int(day))
        result = final >= initial
    except:
        result = False

    return result

def JsonExists(request):
    recievedData = None
    error = ''
    statusCode = 200
    try:
        recievedData = request.json
    except Exception as err:
        error = 'JSON no encontrado'
        statusCode = 400
    
    return recievedData, error, statusCode

def GetTokenOfRequest(request):
    headers = request.headers
    bearer = headers.get('Authorization')
    if bearer is not None:
        token = bearer.split(' ')[1]
    else:
        token = None
    
    return token