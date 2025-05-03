from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException
import time
import unittest

class TestContactForm(unittest.TestCase):
    def setUp(self):
        # Configurar opciones de Chrome
        chrome_options = Options()
        chrome_options.add_argument("--disable-popup-blocking")
        chrome_options.add_argument("--disable-notifications")
        self.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=chrome_options
        )
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 10)
        print("✅ Navegador configurado correctamente")
    
    def test_contact_form_submission(self):
        driver = self.driver
        
        try:
            # Paso 1: Abrir la página de contacto
            driver.get("http://localhost/MacaBlue/Cliente/view/contacto.php")
            print("✅ Página de contacto cargada")
            time.sleep(2)
            driver.save_screenshot('contact_page_loaded.png')
            
            # Paso 2: Llenar el formulario de contacto
            nombre_input = self.wait.until(EC.visibility_of_element_located((By.ID, "nombre")))
            nombre_input.send_keys("Juan Pérez")
            print("✅ Nombre ingresado")
            
            email_input = driver.find_element(By.ID, "email")
            email_input.send_keys("juan.perez@example.com")
            print("✅ Email ingresado")
            
            asunto_input = driver.find_element(By.ID, "asunto")
            asunto_input.send_keys("Consulta sobre productos")
            print("✅ Asunto ingresado")
            
            mensaje_input = driver.find_element(By.ID, "mensaje")
            mensaje_input.send_keys("Este es un mensaje de prueba para el formulario de contacto.")
            print("✅ Mensaje ingresado")
            
            driver.save_screenshot('contact_form_filled.png')
            
            # Paso 3: Enviar el formulario
            submit_button = driver.find_element(By.CSS_SELECTOR, "button[name='enviar_mensaje']")
            driver.execute_script("arguments[0].scrollIntoView(true);", submit_button)
            time.sleep(1)  # Espera breve para estabilizar el scroll
            submit_button.click()
            print("✅ Formulario enviado")
            time.sleep(2)
            
            # Paso 4: Verificar el SweetAlert con el mensaje esperado
            try:
                sweetalert = self.wait.until(EC.visibility_of_element_located((By.CLASS_NAME, "swal2-popup")))
                alert_title = sweetalert.find_element(By.CLASS_NAME, "swal2-title").text
                alert_text = sweetalert.find_element(By.CLASS_NAME, "swal2-html-container").text
                
                assert alert_title == "¡Mensaje enviado!", f"Título inesperado: {alert_title}"
                assert alert_text == "Tu mensaje ha sido enviado con éxito. Te responderemos lo antes posible.", f"Texto inesperado: {alert_text}"
                
                print("✅ SweetAlert mostrado con el mensaje esperado")
                driver.save_screenshot('sweetalert_success.png')
            except TimeoutException:
                print("❌ No se encontró el SweetAlert con el mensaje esperado")
                driver.save_screenshot('sweetalert_error.png')
                self.fail("No se encontró el SweetAlert con el mensaje esperado")
        
        except Exception as e:
            print(f"❌ Error en la prueba del formulario de contacto: {str(e)}")
            driver.save_screenshot('error_contact_form.png')
            self.fail(f"Error en la prueba del formulario de contacto: {str(e)}")
    
    def test_contact_form_invalid_email(self):
        driver = self.driver
        
        try:
            # Paso 1: Abrir la página de contacto
            driver.get("http://localhost/MacaBlue/Cliente/view/contacto.php")
            print("✅ Página de contacto cargada")
            time.sleep(2)
            driver.save_screenshot('contact_page_loaded.png')
            
            # Paso 2: Llenar el formulario de contacto con un correo inválido
            nombre_input = self.wait.until(EC.visibility_of_element_located((By.ID, "nombre")))
            nombre_input.send_keys("Juan Pérez")
            print("✅ Nombre ingresado")
            
            email_input = driver.find_element(By.ID, "email")
            email_input.send_keys("s@")
            print("✅ Correo inválido ingresado")
            
            asunto_input = driver.find_element(By.ID, "asunto")
            asunto_input.send_keys("Consulta sobre productos")
            print("✅ Asunto ingresado")
            
            mensaje_input = driver.find_element(By.ID, "mensaje")
            mensaje_input.send_keys("Este es un mensaje de prueba para el formulario de contacto.")
            print("✅ Mensaje ingresado")
            
            driver.save_screenshot('contact_form_invalid_email_filled.png')
            
            # Paso 3: Intentar enviar el formulario
            submit_button = driver.find_element(By.CSS_SELECTOR, "button[name='enviar_mensaje']")
            driver.execute_script("arguments[0].scrollIntoView(true);", submit_button)
            time.sleep(1)  # Espera breve para estabilizar el scroll
            submit_button.click()
            print("✅ Intento de envío del formulario con correo inválido")
            time.sleep(2)
            
            # Paso 4: Verificar el tooltip de validación HTML5
            if not email_input.get_attribute("validationMessage"):
                print("❌ No se encontró el tooltip de validación HTML5")
                driver.save_screenshot('tooltip_error_invalid_email.png')
                self.fail("No se encontró el tooltip de validación HTML5")
            else:
                validation_message = email_input.get_attribute("validationMessage")
                assert "Introduce texto detrás del signo \"@\". La dirección \"s@\" está incompleta." in validation_message, f"Mensaje inesperado: {validation_message}"
                print(f"✅ Tooltip de validación HTML5 mostrado con el mensaje esperado: {validation_message}")
                driver.save_screenshot('tooltip_invalid_email.png')
        
        except Exception as e:
            print(f"❌ Error en la prueba del formulario de contacto con correo inválido: {str(e)}")
            driver.save_screenshot('error_contact_form_invalid_email.png')
            self.fail(f"Error en la prueba del formulario de contacto con correo inválido: {str(e)}")
    
    def tearDown(self):
        # Cerrar navegador
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()
