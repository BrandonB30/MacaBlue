import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import NoSuchElementException
import os
import time

class TestDetallesProductos(unittest.TestCase):
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

    def test_detalles_producto(self):
        driver = self.driver

        # Paso 1: Ingresar al sitio web
        driver.get(self.base_url)
        print("✅ Página principal cargada")
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step1_pagina_principal.png"))
        time.sleep(2)

        # Paso 2: Buscar o seleccionar un producto del catálogo
        print("🔍 Seleccionando un producto del catálogo...")
        product_cards = driver.find_elements(By.CSS_SELECTOR, ".product-card")
        
        if not product_cards:
            raise NoSuchElementException("No se encontraron tarjetas de productos")
        
        # Obtener el primer producto
        first_card = product_cards[0]
        
        # Paso 3: Hacer clic en "Ver Detalles" del primer producto
        try:
            ver_detalles = first_card.find_element(By.CSS_SELECTOR, "a.btn.btn-primary")
            driver.execute_script("arguments[0].click();", ver_detalles)
            print("✅ Botón 'Ver Detalles' clickeado")
            time.sleep(2)  # Esperar a que el modal se abra
            driver.save_screenshot(os.path.join(self.screenshot_dir, "step3_modal_abierto.png"))
        except Exception as e:
            print(f"❌ Error al hacer clic en 'Ver Detalles': {str(e)}")
            driver.save_screenshot(os.path.join(self.screenshot_dir, "error_click_details.png"))
            raise

        # Paso 4: Verificar que se muestre la información detallada del producto
        print("🔍 Verificando información detallada del producto en el modal...")
        modal = self.wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".modal.show")))
        descripcion = modal.find_element(By.XPATH, "//p[strong[text()='Descripción:']]").text
        color = modal.find_element(By.XPATH, "//p[strong[text()='Color:']]").text
        material = modal.find_element(By.XPATH, "//p[strong[text()='Material:']]").text
        tallas = modal.find_element(By.XPATH, "//p[strong[text()='Tallas:']]").text
        precio = modal.find_element(By.CSS_SELECTOR, ".price").text

        # Validar que los datos sean correctos
        self.assertIn("Descripción: Blusa blanca con diseño casual y cuello en V, ideal para ocasiones informales.", descripcion)
        self.assertIn("Color: Blanco", color)
        self.assertIn("Material: Poliéster", material)
        self.assertIn("Tallas: M,L", tallas)
        self.assertIn("Precio: $45,000.00", precio)

        print(f"✅ Descripción: {descripcion}")
        print(f"✅ Color: {color}")
        print(f"✅ Material: {material}")
        print(f"✅ Tallas: {tallas}")
        print(f"✅ Precio: {precio}")

        # Captura de pantalla de la información detallada
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step4_informacion_detallada.png"))

    def tearDown(self):
        # Cerrar el navegador
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()