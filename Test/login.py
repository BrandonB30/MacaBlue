from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.common.exceptions import NoSuchElementException
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

# Configurar el driver
driver = webdriver.Chrome()

# URL del login
url = "http://localhost/MacaBlue/view/ingreso.php"

# Función para hacer login
def login(email, password):
    driver.get(url)
    
    try:
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

        login_button.click()

        # Esperar posible cambio de URL o mensaje de error
        WebDriverWait(driver, 5).until(lambda d: d.current_url != url or 
                                       "Cliente no encontrado" in d.page_source or 
                                       "Campo obligatorio" in d.page_source)

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
login("kevin1@gmail.com", "12345")

# 2. Login con datos incorrectos
print("🔴 Probando login con datos incorrectos...")
login("usuario_falso@email.com", "contraseña_falsa")

# 3. Campo de email vacío
print("⚠️ Probando login con campo EMAIL vacío...")
login("", "12345")

# 4. Campo de contraseña vacío
print("⚠️ Probando login con campo CONTRASEÑA vacío...")
login("kevin1@gmail.com", "")

# 5. Ambos campos vacíos
print("⚠️ Probando login con ambos campos vacíos...")
login("", "")

# Cerrar navegador
driver.quit()
