from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time, os

def test_login_with_verification_code():
    driver = webdriver.Chrome()
    driver.maximize_window()

    screenshots_dir = "ImgTest"
    os.makedirs(screenshots_dir, exist_ok=True)

    try:
        driver.get("http://localhost/MacaBlue/Admin/login.php")
        driver.save_screenshot(os.path.join(screenshots_dir, "01_login_page.png"))

        # Ingresar usuario y contraseña
        driver.find_element(By.ID, "username").send_keys("dardini2002@gmail.com")
        driver.find_element(By.ID, "password").send_keys("Admin3011$")
        driver.save_screenshot(os.path.join(screenshots_dir, "02_filled_credentials.png"))

        # Clic en el botón de Iniciar Sesión
        driver.find_element(By.ID, "submitButton").click()

        # Esperar a que aparezca el campo del código de verificación
        wait = WebDriverWait(driver, 10)
        code_input = wait.until(EC.visibility_of_element_located((By.ID, "verification_code")))
        driver.save_screenshot(os.path.join(screenshots_dir, "03_code_input_visible.png"))

        # Ingresar el código de verificación
        code_input.send_keys("123456")
        driver.save_screenshot(os.path.join(screenshots_dir, "04_code_filled.png"))

        # Hacer clic nuevamente en el mismo botón (que ahora dice "Verificar Código")
        driver.find_element(By.ID, "submitButton").click()

        # Esperar redirección al dashboard
        wait.until(EC.url_contains("view-dashboard.php"))
        driver.save_screenshot(os.path.join(screenshots_dir, "05_dashboard_loaded.png"))

        # Clic en el enlace "Mensajes"
        mensajes_link = wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Mensajes")))
        mensajes_link.click()
        driver.save_screenshot(os.path.join(screenshots_dir, "06_mensajes_clicked.png"))

        # Verificar que el título de la sección es "Gestión de Mensajes de Contacto"
        mensajes_title = wait.until(EC.presence_of_element_located((By.TAG_NAME, "h2")))  # Cambia el selector si es necesario
        assert mensajes_title.text == "Gestión de Mensajes de Contacto", "❌ El título de la sección no es correcto."
        driver.save_screenshot(os.path.join(screenshots_dir, "07_correct_title.png"))

        print("✅ Prueba completada exitosamente. El título de la sección es correcto.")

    except Exception as e:
        driver.save_screenshot(os.path.join(screenshots_dir, "error.png"))
        print(f"❌ Error durante la prueba: {e}")

    finally:
        time.sleep(3)
        driver.quit()

if __name__ == "__main__":
    test_login_with_verification_code()
