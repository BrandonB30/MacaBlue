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
        chrome_options = Options()
        chrome_options.add_argument("--disable-popup-blocking")
        chrome_options.add_argument("--disable-notifications")
        chrome_options.add_argument("--disable-save-password-bubble")
        chrome_options.add_argument("--disable-infobars")
        chrome_options.add_experimental_option("prefs", {
            "credentials_enable_service": False,
            "profile.password_manager_enabled": False,
        })

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
            driver.get("http://localhost/MacaBlue/Cliente/view/productos.php")
            print("✅ Página de productos cargada")
            time.sleep(2)

            login_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Iniciar sesión")))
            login_link.click()
            print("✅ Clic en Iniciar sesión")

            email_input = self.wait.until(EC.visibility_of_element_located((By.ID, "email")))
            email_input.send_keys("prueba@gmail.com")
            driver.find_element(By.ID, "contrasena").send_keys("123456789")
            print("✅ Credenciales ingresadas")
            driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            print("✅ Formulario de inicio de sesión enviado")
            time.sleep(3)

            product_cards = driver.find_elements(By.CSS_SELECTOR, ".product-card")
            if not product_cards:
                raise NoSuchElementException("No se encontraron tarjetas de productos")
            first_card = product_cards[0]

            try:
                ver_detalles = first_card.find_element(By.CSS_SELECTOR, "a.btn.btn-primary")
                driver.execute_script("arguments[0].click();", ver_detalles)
                print("✅ Botón 'Ver Detalles' clickeado")
                time.sleep(2)
            except Exception as e:
                print(f"❌ Error al hacer clic en 'Ver Detalles': {str(e)}")
                driver.save_screenshot('error_click_details.png')
                raise

            try:
                modal = self.wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".modal.show")))
                add_cart_button = modal.find_element(By.CSS_SELECTOR, "button[type='submit']")
                driver.execute_script("arguments[0].click();", add_cart_button)
                print("✅ Producto añadido al carrito desde el modal")
                time.sleep(2)
            except Exception as e:
                print(f"❌ Error al añadir producto al carrito: {str(e)}")
                driver.save_screenshot('error_add_to_cart.png')
                raise

            try:
                print("⏳ Esperando que se cargue automáticamente la página del carrito...")
                self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "a.btn.btn-primary.mt-3.mb-3")))
                print("✅ Página del carrito cargada automáticamente")
            except Exception as e:
                print(f"❌ La página del carrito no se cargó automáticamente: {str(e)}")
                driver.save_screenshot('error_cart_autoload.png')
                raise

            time.sleep(3)

            print("🔍 Haciendo scroll hacia el botón 'Proceder al Pago'...")
            try:
                boton_proceder = None
                for _ in range(10):
                    try:
                        boton_proceder = self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "a.btn.btn-primary.mt-3.mb-3")))
                        if boton_proceder.is_displayed():
                            break
                    except TimeoutException:
                        pass
                    driver.execute_script("window.scrollBy(0, 500);")
                    time.sleep(1)

                if boton_proceder and boton_proceder.is_displayed():
                    print("✅ Botón 'Proceder al Pago' encontrado")
                    driver.execute_script("arguments[0].scrollIntoView(true);", boton_proceder)
                    driver.save_screenshot('scroll_to_proceed_to_payment.png')
                    boton_proceder.click()
                    print("✅ Clic en 'Proceder al Pago'")
                    time.sleep(3)
                else:
                    raise NoSuchElementException("No se pudo encontrar el botón 'Proceder al Pago' después de hacer scroll.")
            except Exception as e:
                print(f"❌ Error al hacer scroll hacia el botón 'Proceder al Pago': {str(e)}")
                driver.save_screenshot('error_scroll_to_payment.png')
                raise

            print("🔍 Verificando información preliminar...")
            try:
                driver.save_screenshot('debug_before_resumen_pedido.png')

                direccion = self.wait.until(EC.presence_of_element_located((By.ID, "direccion")))
                self.assertTrue(direccion.is_displayed(), "El campo de dirección no está visible")
                print("✅ Dirección de envío validada")

                metodos_pago = driver.find_elements(By.CSS_SELECTOR, ".payment-method input[type='radio']")
                metodos_esperados = ["tarjeta", "paypal", "transferencia"]
                metodos_encontrados = [metodo.get_attribute("value") for metodo in metodos_pago]

                for metodo in metodos_esperados:
                    self.assertIn(metodo, metodos_encontrados, f"No se encontró el método de pago: {metodo}")
                print(f"✅ Métodos de pago validados: {', '.join(metodos_encontrados)}")

                print("🔍 Verificando el resumen del pedido...")
                driver.save_screenshot('debug_resumen_pedido.png')

                resumen_pedido = self.wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, ".card-body")))
                # Buscamos los productos con un xpath más específico
                productos = resumen_pedido.find_elements(
                        By.XPATH,
                        ".//div[contains(@class, 'border-bottom') and .//img and .//span[contains(text(), '$')]]"
                    )

                for producto in productos:
                    nombre = producto.find_element(By.CSS_SELECTOR, ".flex-grow-1 span").text
                    subtotal = producto.find_element(By.CSS_SELECTOR, ".text-end").text
                    print(f"✅ Producto: {nombre}, Subtotal: {subtotal}")

                total_element = driver.find_element(By.XPATH, "//p[contains(text(), 'Total:')]")
                print(f"✅ Total del pedido: {total}")
                driver.save_screenshot('payment_information.png')
            except Exception as e:
                print(f"❌ Error al validar la información preliminar: {str(e)}")
                driver.save_screenshot('error_payment_information.png')
                raise

            # Paso 8: Validar elementos de la pantalla de finalización de compra
            print("🔍 Validando pantalla final de compra...")
            try:
                nombre_producto = self.wait.until(EC.presence_of_element_located((By.XPATH, "//div[contains(text(),'Blusa Rosa Suave')]")))
                self.assertTrue(nombre_producto.is_displayed(), "No se muestra el nombre del producto en el resumen")
                print("✅ Nombre del producto validado en el resumen")

                subtotal = self.wait.until(EC.presence_of_element_located((By.XPATH, "//div[contains(text(),'Subtotal')]/following-sibling::div[contains(text(),'$38,000.00')]")))
                self.assertTrue(subtotal.is_displayed(), "El subtotal no es correcto")
                print("✅ Subtotal validado")

                envio = self.wait.until(EC.presence_of_element_located((By.XPATH, "//div[contains(text(),'Envío')]/following-sibling::div[contains(text(),'Gratis')]")))
                self.assertTrue(envio.is_displayed(), "No se muestra el envío gratis")
                print("✅ Envío gratis validado")

                total = self.wait.until(EC.presence_of_element_located((By.XPATH, "//strong[contains(text(),'Total:')]/following-sibling::strong[contains(text(),'$38,000.00')]")))
                self.assertTrue(total.is_displayed(), "El total final no es correcto")
                print("✅ Total final validado")

                confirmar_pago_btn = self.wait.until(EC.element_to_be_clickable((By.XPATH, "//button[contains(text(),'Confirmar pago')]")))
                self.assertTrue(confirmar_pago_btn.is_displayed(), "Botón 'Confirmar pago' no está visible")
                print("✅ Botón 'Confirmar pago' validado")

                driver.save_screenshot('pantalla_final_pago.png')

            except Exception as e:
                print(f"❌ Error en la validación de la pantalla de pago: {str(e)}")
                driver.save_screenshot('error_pantalla_final_pago.png')
                raise

        except Exception as e:
            print(f"❌ Error en la prueba: {str(e)}")
            driver.save_screenshot('error_test.png')
            self.fail(f"Error en la prueba: {str(e)}")

    def tearDown(self):
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()
