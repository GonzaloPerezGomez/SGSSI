import requests

# URL base
base_url = "http://localhost:81/show_user"

# Lista de valores a probar en el parámetro 'user'
user_values = ['a', 'b', 'c', 'admin', 'test', 'user1']

for user in user_values:
    # Realizar la solicitud GET con el parámetro 'user'
    response = requests.get(base_url, params={'user': user})
    # Verificar si el usuario es correcto
    if response.status_code == 200 and "Información del Usuario" in response.text:
        print(f"User '{user}' -> Usuario válido")
    else:
        print(f"User '{user}' -> Failed (HTTP {response.status_code})")


