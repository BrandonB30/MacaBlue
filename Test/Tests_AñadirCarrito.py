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

driver.find_element(By.LINK_TEXT, "Iniciar sesión").click()
time.sleep(3)

# Rellenar correo y contraseña
driver.find_element(By.ID, "email").send_keys("prueba@gmail.com")
driver.find_element(By.ID, "contrasena").send_keys("123456789")

# Enviar el formulario
driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

# Esperar redirección
time.sleep(3)

# Paso 3: Hacer clic en "View Product" de la primera prenda
driver.find_element(By.XPATH, "(//a[contains(text(),'Ver Detalles')])[1]").click()
time.sleep(3)

# Paso 4: En el modal (detalle del producto), clic en "Add to cart"
add_to_cart_button = driver.find_element(By.XPATH, "//button[contains(text(),'Añadir al carrito')]")
add_to_cart_button.click()
time.sleep(3)

alerta = driver.switch_to.alert
alerta.accept()

carrito_enlace = driver.find_element(By.CSS_SELECTOR, "a[href='/MacaBlue/view/carrito.php']")
carrito_enlace.click()
time.sleep(3)

# Buscar el producto por nombre
producto_esperado = "Blusa Casual Blanca"
productos = driver.find_elements(By.CLASS_NAME, "card-title")
encontrado = False

for producto in productos:
    print("🕵️‍♂️ Encontrado en página:", producto.text.strip())  # Para depuración
    if producto_esperado.lower() in producto.text.strip().lower():
        print("✅ Producto encontrado en el carrito.")
        encontrado = True
        break

if not encontrado:
    print("❌ Error: el producto no está en el carrito.")
    
# Cerrar navegador
driver.quit()
