import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
import os
import time

class TestEstadoPedido(unittest.TestCase):
    def setUp(self):
        # Configurar el driver
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.base_url = "http://localhost/MacaBlue/Cliente/view/productos.php"
        self.wait = WebDriverWait(self.driver, 10)

        # Crear carpeta para capturas de pantalla si no existe
        self.screenshot_dir = "ImgTest"
        if not os.path.exists(self.screenshot_dir):
            os.makedirs(self.screenshot_dir)
            
    def save_debug_info(self, step_name):
        """Guarda una captura de pantalla y el HTML de la página actual para depuración"""
        self.driver.save_screenshot(os.path.join(self.screenshot_dir, f"{step_name}.png"))
        with open(os.path.join(self.screenshot_dir, f"{step_name}_html.txt"), "w", encoding="utf-8") as f:
            f.write(self.driver.page_source)
        print(f"Debug info saved for step: {step_name}")

    def test_estado_pedido(self):
        driver = self.driver

        # Paso 1: Ingresar al sitio web de la tienda
        driver.get(self.base_url)
        print("✅ Página principal cargada")
        self.save_debug_info("step1_pagina_principal")
        time.sleep(2)

        # Paso 2: Iniciar sesión con una cuenta válida
        print("🔍 Buscando el enlace 'Iniciar sesión'...")
        try:
            login_link = self.wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Iniciar sesión")))
            login_link.click()
            print("✅ Clic en 'Iniciar sesión'")
        except TimeoutException:
            print("⚠️ No se encontró 'Iniciar sesión' por texto. Intentando con otros selectores...")
            try:
                # Alternativa: buscar por clase o ID común para botones de login
                login_link = self.wait.until(EC.element_to_be_clickable(
                    (By.CSS_SELECTOR, ".login-button, #login-btn, button[id*='login'], a[href*='login']")))
                login_link.click()
                print("✅ Clic en botón de login alternativo")
            except TimeoutException:
                self.save_debug_info("login_button_not_found")
                self.fail("No se pudo encontrar el botón de inicio de sesión")
        
        self.save_debug_info("step2a_login_form")
        time.sleep(2)

        print("⌨️ Ingresando credenciales...")
        try:
            email_input = self.wait.until(EC.presence_of_element_located((By.ID, "email")))
            email_input.send_keys("prueba@gmail.com")
            password_input = driver.find_element(By.ID, "contrasena")
            password_input.send_keys("123456789")
            driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
            print("✅ Credenciales enviadas")
        except (TimeoutException, NoSuchElementException) as e:
            self.save_debug_info("login_form_error")
            self.fail(f"Error al interactuar con el formulario de login: {str(e)}")
            
        self.save_debug_info("step2b_after_login")
        time.sleep(3)
        
        # Verificar si el login fue exitoso
        print("🔍 Verificando si el login fue exitoso...")
        try:
            self.wait.until(EC.presence_of_element_located(
                (By.CSS_SELECTOR, ".user-menu, .perfil, .cuenta, [class*='user'], [class*='account']")))
            print("✅ Login confirmado exitoso")
        except TimeoutException:
            self.save_debug_info("login_verification_failed")
            print("⚠️ No se pudo confirmar si el login fue exitoso. Continuando...")

        # Paso 3: Navegar a la sección de "Mis pedidos"
        print("🔍 Navegando a la sección 'Mis pedidos'...")
        self.save_debug_info("step3a_before_mis_pedidos")
        
        # Intentar encontrar "Mis pedidos" con múltiples estrategias
        mis_pedidos_encontrado = False
        
        # Lista de selectores para intentar
        selectores = [
            (By.LINK_TEXT, "Mis pedidos"),
            (By.PARTIAL_LINK_TEXT, "pedidos"),
            (By.PARTIAL_LINK_TEXT, "Pedidos"),
            (By.CSS_SELECTOR, "a[href*='pedido'], a[href*='orden'], a.pedidos, a.mis-pedidos"),
            (By.XPATH, "//a[contains(text(), 'pedido') or contains(text(), 'Pedido') or contains(@href, 'pedido')]"),
            (By.XPATH, "//a[contains(@class, 'pedido') or contains(@id, 'pedido')]"),
            (By.CSS_SELECTOR, ".fa-shopping-bag, .fa-box, .fa-truck, .fa-list-alt")
        ]
        
        for by, selector in selectores:
            try:
                print(f"Intentando con selector: {by}='{selector}'")
                elemento = self.wait.until(EC.element_to_be_clickable((by, selector)))
                print(f"✅ Elemento encontrado: {elemento.tag_name}, texto: '{elemento.text}', clase: '{elemento.get_attribute('class')}'")
                elemento.click()
                mis_pedidos_encontrado = True
                print(f"✅ Clic en elemento de 'Mis pedidos' exitoso con {by}='{selector}'")
                break
            except:
                print(f"❌ No se encontró con {by}='{selector}'")
        
        if not mis_pedidos_encontrado:
            # Último recurso: buscar en la estructura de menú
            try:
                menu_items = driver.find_elements(By.CSS_SELECTOR, "nav a, .menu a, .navbar a, .sidebar a, header a, footer a")
                for item in menu_items:
                    if "pedido" in item.text.lower() or "order" in item.text.lower():
                        print(f"✅ Encontrado ítem de menú: '{item.text}'")
                        item.click()
                        mis_pedidos_encontrado = True
                        break
            except:
                print("❌ Error al buscar en menús de navegación")
        
        self.save_debug_info("step3b_after_click_mis_pedidos")
        time.sleep(3)
        
        if not mis_pedidos_encontrado:
            self.fail("No se pudo encontrar el enlace a 'Mis pedidos'")
            
        # Paso 4: Buscar y hacer clic específicamente en la tarjeta del Pedido #10
        print("🔍 Buscando específicamente el Pedido #10...")
        self.save_debug_info("step4a_buscar_pedido_10")
        
        # Basado en la imagen compartida, buscar la tarjeta que contiene "Pedido #10"
        pedido_encontrado = False
        
        # Estrategia 1: Buscar por el encabezado "Pedido #10"
        try:
            # Buscar el texto exacto "Pedido #10"
            pedido_header = self.wait.until(EC.presence_of_element_located(
                (By.XPATH, "//*[contains(text(), 'Pedido #10')]")))
            # Navegar hacia arriba para encontrar la tarjeta completa
            pedido_card = pedido_header
            for _ in range(5):  # Subir hasta 5 niveles en el DOM
                try:
                    if "card" in pedido_card.get_attribute("class") or pedido_card.tag_name == "div":
                        print(f"✅ Encontrada tarjeta del Pedido #10")
                        break
                    pedido_card = pedido_card.find_element(By.XPATH, "..")
                except:
                    break
                    
            # Estrategia 2: Buscar directamente el botón "Ver Detalles" dentro de la tarjeta
            ver_detalles_btn = None
            try:
                # Buscar dentro de la tarjeta o en toda la página si es necesario
                ver_detalles_btn = pedido_card.find_element(By.XPATH, ".//a[contains(text(), 'Ver Detalles')]")
                print("✅ Botón 'Ver Detalles' encontrado dentro de la tarjeta")
            except:
                try:
                    # Buscar cerca del texto "Pedido #10"
                    ver_detalles_btn = driver.find_element(
                        By.XPATH, "//a[contains(text(), 'Ver Detalles') and preceding::*[contains(text(), 'Pedido #10')]]")
                    print("✅ Botón 'Ver Detalles' encontrado cerca del texto 'Pedido #10'")
                except:
                    print("⚠️ No se encontró el botón 'Ver Detalles', intentando con otros selectores...")
                    try:
                        # Buscar cualquier elemento con icono o clase que parezca un botón de detalles
                        ver_detalles_btn = driver.find_element(
                            By.XPATH, "//*[contains(@class, 'detalle') or contains(@class, 'ver') or contains(@class, 'eye')]")
                        print("✅ Encontrado posible botón de detalles por clase")
                    except:
                        print("❌ No se pudo encontrar un botón de detalles")
            
            # Hacer clic en el botón de detalles si se encontró
            if ver_detalles_btn:
                ver_detalles_btn.click()
                pedido_encontrado = True
                print("✅ Clic exitoso en 'Ver Detalles'")
            else:
                # Último recurso: hacer clic en la tarjeta completa
                pedido_card.click()
                pedido_encontrado = True
                print("✅ Clic exitoso en la tarjeta del Pedido #10")
                
        except Exception as e:
            print(f"⚠️ Error al buscar Pedido #10 específicamente: {str(e)}")
            # Intento alternativo basado en la imagen compartida
            try:
                # Buscar elementos que contengan la fecha, estado y total visibles en la imagen
                fecha_element = driver.find_element(By.XPATH, "//div[contains(text(), 'Fecha:') and following-sibling::*[contains(text(), '2025-05-07')]]")
                # Subir al contenedor padre (tarjeta)
                pedido_card = fecha_element
                for _ in range(3):  # Subir hasta 3 niveles en el DOM
                    try:
                        pedido_card = pedido_card.find_element(By.XPATH, "..")
                    except:
                        break
                        
                # Buscar el botón Ver Detalles
                ver_detalles_btn = pedido_card.find_element(By.XPATH, ".//a[contains(text(), 'Ver Detalles')]")
                ver_detalles_btn.click()
                pedido_encontrado = True
                print("✅ Clic exitoso en 'Ver Detalles' usando enfoque alternativo")
            except Exception as e2:
                print(f"❌ Error en el enfoque alternativo: {str(e2)}")
        
        self.save_debug_info("step4b_despues_clic_pedido")
        time.sleep(3)
        
        # Si aún no se ha encontrado, intentar un enfoque más directo basado en la imagen
        if not pedido_encontrado:
            try:
                # Buscar directamente el botón de Ver Detalles con el ícono del ojo
                ver_detalles_btn = driver.find_element(By.XPATH, "//a[contains(@class, 'btn') and contains(text(), 'Ver Detalles')]")
                ver_detalles_btn.click()
                pedido_encontrado = True
                print("✅ Clic exitoso en botón 'Ver Detalles' con enfoque directo")
            except:
                print("❌ No se pudo encontrar el botón 'Ver Detalles' con enfoque directo")
                
        if not pedido_encontrado:
            self.fail("No se pudo encontrar o interactuar con el Pedido #10")
            
        # Paso 5: Verificar que se muestre la información del pedido
        print("🔍 Verificando información del pedido...")
        self.save_debug_info("step5_modal_pedido")
        time.sleep(2)
        
        # Verificar si hay un modal o contenedor de detalles
        try:
            # Intentar varios selectores para el modal/detalles
            modal_encontrado = False
            for selector in [".modal", ".modal-content", ".popup", ".detalle-pedido", "#modalDetalles", "[id*='modal']", "[class*='detalle']", "[class*='detail']"]:
                try:
                    modal = self.wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, selector)))
                    print(f"✅ Encontrado contenedor de detalles con selector: '{selector}'")
                    modal_encontrado = True
                    break
                except TimeoutException:
                    continue
                    
            if not modal_encontrado:
                # Si no se encontró un modal, podríamos estar ya en la página de detalles
                print("⚠️ No se encontró un modal, verificando si estamos en la página de detalles...")
                # Verificar si estamos en la página de detalles
                
            # Validar la información del pedido según lo que vimos en la imagen
            # 1. Verificar número de pedido
            pedido_numero = False
            for selector in ["h1", "h2", "h3", "h4", ".title", ".header", ".pedido-title"]:
                elementos = driver.find_elements(By.CSS_SELECTOR, selector)
                for elem in elementos:
                    if "Pedido #10" in elem.text:
                        pedido_numero = True
                        print(f"✅ Verificado número de pedido: {elem.text}")
                        break
                if pedido_numero:
                    break
                    
            # 2. Verificar fecha
            fecha_verificada = False
            elementos_fecha = driver.find_elements(By.XPATH, "//*[contains(text(), '2025-05-07')]")
            if elementos_fecha:
                fecha_verificada = True
                print(f"✅ Verificada fecha del pedido: {elementos_fecha[0].text}")
                
            # 3. Verificar total
            total_verificado = False
            elementos_total = driver.find_elements(By.XPATH, "//*[contains(text(), '$45,000.00') or contains(text(), '45000')]")
            if elementos_total:
                total_verificado = True
                print(f"✅ Verificado total del pedido: {elementos_total[0].text}")
                
            # 4. Verificar estado
            estado_verificado = False
            elementos_estado = driver.find_elements(By.XPATH, "//*[contains(text(), 'En Proceso') or contains(text(), 'en proceso')]")
            if elementos_estado:
                estado_verificado = True
                print(f"✅ Verificado estado del pedido: {elementos_estado[0].text}")
                
            # Validar que encontramos toda la información
            if pedido_numero and fecha_verificada and total_verificado and estado_verificado:
                print("✅ Todas las validaciones del pedido #10 fueron exitosas")
            else:
                print("⚠️ No se pudieron validar todos los campos del pedido:")
                print(f"  - Número de pedido: {'✅' if pedido_numero else '❌'}")
                print(f"  - Fecha: {'✅' if fecha_verificada else '❌'}")
                print(f"  - Total: {'✅' if total_verificado else '❌'}")
                print(f"  - Estado: {'✅' if estado_verificado else '❌'}")
                
                # Si no se pudieron validar todos los campos con los selectores específicos,
                # intentar verificar si al menos la información está presente en la página
                page_text = driver.find_element(By.TAG_NAME, "body").text
                if "Pedido #10" in page_text and "2025-05-07" in page_text and ("$45,000.00" in page_text or "45000" in page_text) and "En Proceso" in page_text:
                    print("✅ Se encontró toda la información del pedido en la página, aunque no se pudo validar con selectores específicos")
                else:
                    self.save_debug_info("validacion_fallida")
                    self.fail("No se pudo validar toda la información del pedido")
                
        except Exception as e:
            self.save_debug_info("error_validacion")
            self.fail(f"Error durante la validación del pedido: {str(e)}")
            
        print("✅ Prueba completada con éxito")

    def tearDown(self):
        # Cerrar el navegador
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()