from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
import time

# Configurar el driver
driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()))
driver.maximize_window()

# Paso 1: Abrir el sitio web
driver.get("http://localhost/MacaBlue/view/productos.php")
time.sleep(3)

# Paso 2: Iniciar sesión
driver.find_element(By.LINK_TEXT, "Iniciar sesión").click()
time.sleep(3)

# Rellenar correo y contraseña
driver.find_element(By.ID, "email").send_keys("prueba@gmail.com")
driver.find_element(By.ID, "contrasena").send_keys("123456789")

# Enviar el formulario
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
time.sleep(3)

# Paso 3: Hacer clic en "Ver Detalles" de la primera prenda
driver.find_element(By.XPATH, "(//a[contains(text(),'Ver Detalles')])[1]").click()
time.sleep(3)

# Paso 4: Añadir al carrito
add_to_cart_button = driver.find_element(By.XPATH, "//button[contains(text(),'Añadir al carrito')]")
add_to_cart_button.click()
time.sleep(3)

# Aceptar alerta de "Producto agregado"
alerta = driver.switch_to.alert
alerta.accept()

# Paso 5: Ir al carrito
carrito_enlace = driver.find_element(By.CSS_SELECTOR, "a[href='/MacaBlue/view/carrito.php']")
carrito_enlace.click()
time.sleep(3)

# Paso 6: Buscar el producto en el carrito
producto_esperado = "Blusa Casual Blanca"
productos = driver.find_elements(By.CLASS_NAME, "card-title")
encontrado = False

for index, producto in enumerate(productos):
    print(f"🕵️‍♂️ Producto encontrado en página: {producto.text.strip()}")
    if producto_esperado.lower() in producto.text.strip().lower():
        print("✅ Producto encontrado en el carrito.")

        # Paso 7: Eliminar el producto encontrado
        eliminar_botones = driver.find_elements(By.XPATH, "//button[contains(text(),'Eliminar')]")
        if index < len(eliminar_botones):
            eliminar_botones[index].click()
            time.sleep(2)  # Darle tiempo a que aparezca el alert

            # Aceptar alerta de confirmación de eliminar
            alerta = driver.switch_to.alert
            alerta.accept()
            print("🗑️ Producto eliminado del carrito y alerta aceptada.")
        else:
            print("⚠️ No se encontró el botón de eliminar para este producto.")

        encontrado = True
        time.sleep(2)  # Esperar que se procese la eliminación
        break

if not encontrado:
    print("❌ Error: el producto no está en el carrito.")



# Paso 8: Cerrar navegador
driver.quit()
