from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import os

# Configurar el driver
driver = webdriver.Chrome()

# Crear carpeta para capturas de pantalla si no existe
screenshot_dir = "imgTest"
if not os.path.exists(screenshot_dir):
    os.makedirs(screenshot_dir)

# URL del login
url = "http://localhost/MacaBlue/Cliente/view/ingreso.php"

# Función para hacer login
def login(email, password, test_name):
    driver.get(url)
    
    try:
        # Captura inicial: Página de login cargada
        driver.save_screenshot(os.path.join(screenshot_dir, f"{test_name}_step1_login_page.png"))

        # Espera a que los campos estén disponibles
        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.NAME, "email")))
        
        email_input = driver.find_element(By.NAME, "email")
        password_input = driver.find_element(By.NAME, "contrasena")
        login_button = driver.find_element(By.XPATH, "//button[@type='submit']")

        # Limpiar y rellenar
        email_input.clear()
        password_input.clear()
        email_input.send_keys(email)
        password_input.send_keys(password)

        # Captura antes de enviar el formulario
        driver.save_screenshot(os.path.join(screenshot_dir, f"{test_name}_step2_filled_form.png"))

        login_button.click()

        # Esperar posible cambio de URL o mensaje de error
        WebDriverWait(driver, 5).until(lambda d: d.current_url != url or 
                                       "Cliente no encontrado" in d.page_source or 
                                       "Campo obligatorio" in d.page_source)

        # Captura después de intentar iniciar sesión
        driver.save_screenshot(os.path.join(screenshot_dir, f"{test_name}_step3_after_login_attempt.png"))

        try:
            # Verificar error de credenciales incorrectas
            if "Cliente no encontrado" in driver.page_source:
                print("🔴 Login fallido: Credenciales incorrectas.")
            # Verificar error de campos vacíos (ajusta el texto exacto si tu app da otro mensaje)
            elif "Campo obligatorio" in driver.page_source:
                print("⚠️ Login fallido: Campo vacío.")
            else:
                print("🟢 Login exitoso: ¡Acceso concedido!")

        except NoSuchElementException:
            print("🟢 Login exitoso: ¡Acceso concedido!")

    except Exception as e:
        print(f"⚠️ Error durante el proceso de login: {str(e)}")

# --- PRUEBAS ---

# 1. Login correcto
print("🟢 Probando login correcto...")
login("prueba@gmail.com", "123456789", "test_login_correcto")

# 2. Login con datos incorrectos
print("🔴 Probando login con datos incorrectos...")
login("usuario_falso@email.com", "contraseña_falsa", "test_login_incorrecto")

# 3. Campo de email vacío
print("⚠️ Probando login con campo EMAIL vacío...")
login("", "12345", "test_email_vacio")

# 4. Campo de contraseña vacío
print("⚠️ Probando login con campo CONTRASEÑA vacío...")
login("kevin1@gmail.com", "", "test_password_vacio")

# 5. Ambos campos vacíos
print("⚠️ Probando login con ambos campos vacíos...")
login("", "", "test_ambos_vacios")

# Cerrar navegador
driver.quit()
