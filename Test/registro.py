from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import os

# Configurar navegador
driver = webdriver.Chrome()
driver.maximize_window()

# Crear carpeta para capturas de pantalla si no existe
screenshot_dir = "imgTest"
if not os.path.exists(screenshot_dir):
    os.makedirs(screenshot_dir)

# URL principal
print("Abriendo página principal...")
driver.get("http://localhost/MacaBlue/Cliente/view/productos.php")
time.sleep(2)

# Paso 1: Iniciar sesión
print("Esperando botón 'Iniciar sesión'...")
iniciar_sesion_link = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable((By.LINK_TEXT, "Iniciar sesión"))
)
time.sleep(1)
print("Dando clic en 'Iniciar sesión'...")
iniciar_sesion_link.click()
time.sleep(3)
driver.save_screenshot(os.path.join(screenshot_dir, "step1_iniciar_sesion.png"))

# Paso 2: Ir a "Regístrate aquí"
print("Esperando enlace 'Regístrate aquí'...")
registrate_aqui_link = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable((By.LINK_TEXT, "Regístrate aquí"))
)
time.sleep(1)
print("Dando clic en 'Regístrate aquí'...")
registrate_aqui_link.click()
time.sleep(3)
driver.save_screenshot(os.path.join(screenshot_dir, "step2_registro_pagina.png"))

# Paso 3: Llenar formulario
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
driver.find_element(By.NAME, "email").send_keys("juanperez9@test.com")
time.sleep(0.5)
driver.find_element(By.NAME, "contrasena").send_keys("123456")
time.sleep(1)
driver.save_screenshot(os.path.join(screenshot_dir, "step3_formulario_lleno.png"))

# Paso 4: Enviar formulario
print("Enviando formulario de registro...")
driver.find_element(By.XPATH, "//button[contains(text(),'Registrarse')]").click()
time.sleep(3)
driver.save_screenshot(os.path.join(screenshot_dir, "step4_formulario_enviado.png"))

# Paso 5: Validar SweetAlert
try:
    print("Esperando SweetAlert visible...")
    sweetalert = WebDriverWait(driver, 5).until(
        EC.visibility_of_element_located((By.CLASS_NAME, "swal2-popup"))
    )

    # Esperar un poco más por animación
    time.sleep(1.5)

    # Capturar contenido del SweetAlert
    swal_title = driver.find_element(By.CLASS_NAME, "swal2-title").text
    swal_text = driver.find_element(By.CLASS_NAME, "swal2-html-container").text

    print(f"Título del SweetAlert: {swal_title}")
    print(f"Texto del SweetAlert: {swal_text}")

    assert "Registro exitoso" in swal_text or "Redirigiendo" in swal_text
    print("✅ SweetAlert detectado correctamente.")
    driver.save_screenshot(os.path.join(screenshot_dir, "step5_sweetalert_exitoso.png"))

except Exception as e:
    print(f"❌ No se detectó correctamente el SweetAlert: {e}")


# Cerrar navegador
driver.quit()
