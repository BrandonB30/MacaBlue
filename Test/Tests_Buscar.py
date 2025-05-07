import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import os
import time

class TestBusquedaProducto(unittest.TestCase):
    def setUp(self):
        # Configurar el driver
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.base_url = "http://localhost/MacaBlue/Cliente/view/productos.php"

        # Crear carpeta para capturas de pantalla si no existe
        self.screenshot_dir = "ImgTest"
        if not os.path.exists(self.screenshot_dir):
            os.makedirs(self.screenshot_dir)

    def test_buscar_producto(self):
        driver = self.driver
        wait = WebDriverWait(driver, 10)

        # Paso 1: Abrir la página principal
        driver.get(self.base_url)
        print("✅ Página principal cargada")
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step1_pagina_principal.png"))

        # Paso 2: Buscar el campo de búsqueda
        print("🔍 Buscando el campo de búsqueda...")
        search_input = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, "input[placeholder='Buscar']")))

        # Paso 3: Ingresar el término de búsqueda y esperar un momento
        print("⌨️ Ingresando término de búsqueda: 'pantalon'")
        search_input.clear()
        search_input.send_keys("pantalon")
        time.sleep(2)  # Esperar 2 segundos para que se vea el texto ingresado
        search_input.send_keys(Keys.RETURN)
        time.sleep(3)  # Esperar a que se carguen los resultados
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step2_busqueda_realizada.png"))

        # Paso 4: Validar que los resultados se muestran
        print("✅ Validando que los resultados se muestran...")
        resultados_titulo = wait.until(EC.presence_of_element_located((By.XPATH, "//h1[contains(text(), 'Resultados para: pantalon')]")))
        self.assertIn("Resultados para: pantalon", resultados_titulo.text)
        print("✅ Resultados encontrados: 'Resultados para: pantalon'")
        driver.save_screenshot(os.path.join(self.screenshot_dir, "step3_resultados_mostrados.png"))

    def tearDown(self):
        # Cerrar el navegador
        if hasattr(self, 'driver'):
            self.driver.quit()
            print("✅ Navegador cerrado")

if __name__ == "__main__":
    unittest.main()