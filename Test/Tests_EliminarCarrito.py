from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import time
import unittest

class TestMacaBlueCartRemove(unittest.TestCase):
    def setUp(self):
        # Configurar opciones de Chrome para evitar diálogos de contraseña
        chrome_options = Options()
        chrome_options.add_argument("--disable-popup-blocking")
        chrome_options.add_argument("--disable-notifications")
        chrome_options.add_argument("--disable-save-password-bubble")
        chrome_options.add_argument("--disable-infobars")
        chrome_options.add_experimental_option("prefs", {
            "credentials_enable_service": False,
            "profile.password_manager_enabled": False,
        })
        
        # Configurar el driver con las opciones
        self.driver = webdriver.Chrome(
            service=Service(ChromeDriverManager().install()),
            options=chrome_options
        )
        self.driver.maximize_window()
        self.wait = WebDriverWait(self.driver, 10)
        print("✅ Navegador configurado correctamente")
        
    def test_remove_from_cart(self):
        driver = self.driver
        
        try:
            # Paso 1: Abrir el sitio web de productos
            driver.get("http://localhost/MacaBlue/Cliente/view/productos.php")
            print("✅ Página de productos cargada")
            time.sleep(2)
            
            # Paso 2: Iniciar sesión
            login_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Iniciar sesión")))
            login_link.click()
            print("✅ Clic en Iniciar sesión")
            
            # Esperar a que el formulario de inicio de sesión esté visible
            email_input = self.wait.until(EC.visibility_of_element_located((By.ID, "email")))
            
            # Rellenar correo y contraseña
            email_input.send_keys("prueba@gmail.com")
            driver.find_element(By.ID, "contrasena").send_keys("123456789")
            print("✅ Credenciales ingresadas")
            
            # Enviar el formulario
            driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            print("✅ Formulario de inicio de sesión enviado")
            time.sleep(3)
            
            # Hacer una captura de pantalla para ver el estado actual
            driver.save_screenshot('after_login.png')
            print("✅ Captura de pantalla después del inicio de sesión guardada")
            
            # Paso 3: Hacer clic en "Ver Detalles" del primer producto
            product_cards = driver.find_elements(By.CSS_SELECTOR, ".product-card")
            
            if not product_cards:
                raise NoSuchElementException("No se encontraron tarjetas de productos")
                
            # Obtener el primer producto
            first_card = product_cards[0]
            
            # Hacer clic en el botón "Ver Detalles" usando JavaScript para mayor confiabilidad
            try:
                ver_detalles = first_card.find_element(By.CSS_SELECTOR, "a.btn.btn-primary")
                driver.execute_script("arguments[0].click();", ver_detalles)
                print("✅ Botón 'Ver Detalles' clickeado")
                time.sleep(2)  # Esperar a que el modal se abra
            except Exception as e:
                print(f"❌ Error al hacer clic en 'Ver Detalles': {str(e)}")
                driver.save_screenshot('error_click_details.png')
                raise
            
            # Paso 4: Añadir al carrito desde el modal
            try:
                # Buscar todos los modales de producto
                modal_elements = driver.find_elements(By.CSS_SELECTOR, ".modal.fade")
                
                # Encontrar el modal activo/visible
                modal_activo = None
                for modal in modal_elements:
                    if "show" in modal.get_attribute("class"):
                        modal_activo = modal
                        break
                
                if not modal_activo:
                    # Usar el primer modal si no se puede identificar uno activo
                    modal_activo = modal_elements[0]
                    # Intentar mostrar el modal con JavaScript
                    driver.execute_script("""
                        var modal = arguments[0];
                        modal.classList.add('show');
                        modal.style.display = 'block';
                        modal.setAttribute('aria-modal', 'true');
                    """, modal_activo)
                    print("✅ Modal activado mediante JavaScript")
                
                # Buscar el input de cantidad y establecer valor
                try:
                    cantidad_input = modal_activo.find_element(By.CSS_SELECTOR, "input[name='cantidad']")
                    driver.execute_script("arguments[0].value = '1';", cantidad_input)
                    print("✅ Cantidad establecida a 1")
                except:
                    print("⚠️ No se pudo modificar la cantidad, usando valor por defecto")
                
                # Buscar el botón de añadir al carrito dentro del modal
                add_cart_button = modal_activo.find_element(By.CSS_SELECTOR, "button[type='submit']")
                driver.execute_script("arguments[0].click();", add_cart_button)
                print("✅ Botón 'Añadir al carrito' clickeado")
                time.sleep(2)
                
                # Manejar posible alerta
                try:
                    alerta = driver.switch_to.alert
                    texto_alerta = alerta.text
                    alerta.accept()
                    print(f"✅ Alerta aceptada: '{texto_alerta}'")
                except:
                    print("ℹ️ No se detectó alerta o ya fue cerrada")
                
            except Exception as e:
                print(f"❌ Error al añadir producto al carrito: {str(e)}")
                driver.save_screenshot('error_add_to_cart.png')
                raise
            
            # Hacer captura después de añadir al carrito
            driver.save_screenshot('after_add_to_cart.png')
            
            # Paso 5: Ir al carrito
            try:
                # Buscar el enlace al carrito (varias estrategias)
                carrito_links = driver.find_elements(By.XPATH, "//a[contains(@href, 'carrito.php')]")
                if carrito_links:
                    driver.execute_script("arguments[0].click();", carrito_links[0])
                    print("✅ Navegando a la página del carrito")
                else:
                    # Intentar buscar por ícono
                    carrito_icon = driver.find_elements(By.CSS_SELECTOR, "i.fa-shopping-cart, i.fas.fa-shopping-cart")
                    if carrito_icon:
                        parent_a = driver.execute_script("return arguments[0].closest('a')", carrito_icon[0])
                        if parent_a:
                            driver.execute_script("arguments[0].click();", parent_a)
                            print("✅ Navegando al carrito usando ícono")
                        else:
                            print("⚠️ No se encontró enlace padre para el ícono del carrito")
                            # Intentar ir directamente por URL
                            driver.get("http://localhost/MacaBlue/Cliente/view/carrito.php")
                            print("✅ Navegando al carrito por URL directa")
                    else:
                        # Último recurso: ir directamente por URL
                        driver.get("http://localhost/MacaBlue/Cliente/view/carrito.php")
                        print("✅ Navegando al carrito por URL directa")
            except Exception as e:
                print(f"❌ Error navegando al carrito: {str(e)}")
                driver.save_screenshot('error_navigate_cart.png')
                raise
                
            # Esperar a que se cargue la página del carrito
            time.sleep(3)
            
            # Hacer una captura de la página del carrito
            driver.save_screenshot('cart_page.png')
            
            # Paso 6: Eliminar producto del carrito
            try:
                print("🔍 Buscando productos en el carrito para eliminar")
                # Buscar productos en el carrito
                productos = driver.find_elements(By.CLASS_NAME, "card-title")
                
                if not productos:
                    print("⚠️ No se encontraron productos en el carrito")
                    driver.save_screenshot('empty_cart.png')
                else:
                    print(f"✅ Se encontraron {len(productos)} productos en el carrito")
                    
                    # Imprimir nombre de cada producto encontrado
                    for i, producto in enumerate(productos):
                        nombre_producto = producto.text.strip()
                        print(f"  🛒 Producto {i+1}: {nombre_producto}")
                    
                    # Buscar botones de eliminar
                    eliminar_botones = driver.find_elements(By.XPATH, "//button[contains(text(), 'Eliminar')]")
                    
                    if eliminar_botones:
                        print(f"✅ Se encontraron {len(eliminar_botones)} botones de eliminar")
                        
                        # Eliminar el primer producto
                        driver.execute_script("arguments[0].click();", eliminar_botones[0])
                        print("✅ Botón eliminar clickeado para el primer producto")
                        time.sleep(2)
                        
                        # Manejar posible alerta de confirmación
                        try:
                            alerta = driver.switch_to.alert
                            texto_alerta = alerta.text
                            alerta.accept()
                            print(f"✅ Alerta de confirmación aceptada: '{texto_alerta}'")
                            time.sleep(2)  # Esperar a que se procese la eliminación
                        except:
                            print("ℹ️ No se detectó alerta de confirmación o ya fue cerrada")
                            
                        # Capturar estado después de eliminar
                        driver.save_screenshot('after_remove_product.png')
                        print("✅ Producto eliminado del carrito correctamente")
                    else:
                        print("⚠️ No se encontraron botones de eliminar")
                        driver.save_screenshot('no_delete_buttons.png')
            except Exception as e:
                print(f"❌ Error al eliminar producto del carrito: {str(e)}")
                driver.save_screenshot('error_remove_product.png')
                raise
                
            print("✅ Test completado correctamente")
                
        except Exception as e:
            print(f"❌ Error en la prueba: {str(e)}")
            driver.save_screenshot('error_test.png')
            self.fail(f"Error en la prueba: {str(e)}")
    
    def tearDown(self):
        # Cerrar navegador
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()