from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# Configurar navegador
driver = webdriver.Chrome()

# URL principal
print("Abriendo página principal...")
driver.get("http://localhost/MacaBlue/view/productos.php")  # <-- Cambia a tu URL real si es diferente
time.sleep(2)

# ---------- Paso 1: Dar clic en "Iniciar sesión" ----------
print("Esperando botón 'Iniciar sesión'...")
iniciar_sesion_link = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable((By.LINK_TEXT, "Iniciar sesión"))
)
time.sleep(1)
print("Dando clic en 'Iniciar sesión'...")
iniciar_sesion_link.click()
time.sleep(3)

# ---------- Paso 2: Dar clic en "Regístrate aquí" ----------
print("Esperando enlace 'Regístrate aquí'...")
registrate_aqui_link = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable((By.LINK_TEXT, "Regístrate aquí"))
)
time.sleep(1)
print("Dando clic en 'Regístrate aquí'...")
registrate_aqui_link.click()
time.sleep(3)

# ---------- Paso 3: Llenar los datos de registro ----------
print("Esperando campos de registro...")
WebDriverWait(driver, 10).until(
    EC.presence_of_element_located((By.NAME, "nombre"))
)
time.sleep(1)

print("Llenando formulario de registro...")
driver.find_element(By.NAME, "nombre").send_keys("Juan")
time.sleep(0.5)
driver.find_element(By.NAME, "apellido").send_keys("Pérez")
time.sleep(0.5)
driver.find_element(By.NAME, "email").send_keys("juanperez3@test.com")
time.sleep(0.5)
driver.find_element(By.NAME, "contrasena").send_keys("123456")
time.sleep(1) 

# ---------- Paso 4: Enviar el formulario ----------
print("Enviando formulario de registro...")
driver.find_element(By.XPATH, "//button[contains(text(),'Registrarse')]").click()
time.sleep(5)

print("Registro completado exitosamente 🎉")


# Validar campos no vacíos
nombre = driver.find_element(By.NAME, "nombre").get_attribute("value")
apellido = driver.find_element(By.NAME, "apellido").get_attribute("value")
email = driver.find_element(By.NAME, "email").get_attribute("value")
contrasena = driver.find_element(By.NAME, "contrasena").get_attribute("value")

if not nombre or not apellido or not email or not contrasena:
    print("Error: Hay campos vacíos. No se puede enviar el formulario.")
    driver.quit()
    exit()

# Enviar formulario
print("Enviando formulario de registro...")
driver.find_element(By.XPATH, "//button[contains(text(),'Registrarse')]").click()
time.sleep(3)

# Validar error de registro
try:
    mensaje_error = WebDriverWait(driver, 5).until(
        EC.presence_of_element_located((By.CLASS_NAME, "mensaje-error"))  # <-- Ajusta el selector a tu HTML
    )
    print("Error durante el registro:", mensaje_error.text)
except:
    print("Registro completado exitosamente 🎉")

# Cerrar navegador
driver.quit()


# Cerrar navegador
driver.quit()
