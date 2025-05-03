import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time
import os

class TestInstagramIcon(unittest.TestCase):
    def setUp(self):
        # Configurar el driver (asegúrate de tener el driver correcto en tu PATH)
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.base_url = "http://localhost/MacaBlue/Cliente/view/contacto.php"  # Cambia esto si es necesario

        # Crear carpeta para capturas de pantalla si no existe
        self.screenshot_dir = "imgTest"
        if not os.path.exists(self.screenshot_dir):
            os.makedirs(self.screenshot_dir)

    def test_instagram_icon(self):
        driver = self.driver
        driver.get(self.base_url)

        # Tomar captura de pantalla inicial
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step1_homepage_instagram.png"))

        # Navegar a la sección de contacto
        contact_section = driver.find_element(By.LINK_TEXT, "Contacto")  # Ajusta el selector si es necesario
        contact_section.click()
        time.sleep(2)  # Esperar a que cargue la sección

        # Tomar captura de pantalla después de ir a contacto
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step2_contact_section_instagram.png"))

        # Hacer scroll hasta el icono de Instagram
        instagram_icon = driver.find_element(By.CSS_SELECTOR, "a[href*='instagram.com']")  # Ajusta el selector si es necesario
        actions = ActionChains(driver)
        actions.move_to_element(instagram_icon).perform()
        time.sleep(1)  # Esperar un momento para el scroll

        # Tomar captura de pantalla después del scroll
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step3_scrolled_to_instagram.png"))

        # Guardar el número actual de ventanas antes de hacer clic
        original_window = driver.current_window_handle
        windows_before = driver.window_handles
        
        # Hacer clic en el icono de Instagram
        instagram_icon.click()
        time.sleep(5)  # Esperar a que se abra la página de Instagram
        
        # Comprobar si se abrió una nueva pestaña
        windows_after = driver.window_handles
        
        # Si hay nuevas pestañas, cambiar a la nueva
        if len(windows_after) > len(windows_before):
            # Cambiar a la nueva pestaña (la última abierta)
            new_window = [window for window in windows_after if window != original_window][0]
            driver.switch_to.window(new_window)
        
        # Si no hay nuevas pestañas, continuamos en la misma
        # (Instagram se abrió en la misma ventana)
        
        # A partir de aquí verificamos que estamos en la página de Instagram,
        # independientemente de si se abrió en una nueva pestaña o en la misma
        
        # Verificar que estamos en la página de Instagram
        self.assertIn("instagram.com", driver.current_url)
        
        # Esperar a que la página cargue completamente
        WebDriverWait(driver, 10).until(
            EC.presence_of_element_located((By.TAG_NAME, "body"))
        )
        
        # Verificar que el título contiene "Instagram"
        self.assertIn("Instagram", driver.title)
        
        # Tomar captura de pantalla de la página de Instagram (página de inicio de sesión)
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step4_instagram_login_page.png"))
        
        # Imprimir mensaje de confirmación
        print("Prueba exitosa: La página de Instagram se abrió correctamente")
        print(f"URL actual: {driver.current_url}")
        print(f"Título de la página: {driver.title}")

    def tearDown(self):
        # Cerrar el navegador
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()