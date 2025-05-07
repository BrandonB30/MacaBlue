import unittest
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

# -*- coding: utf-8 -*-

class TestMensajesAdmin(unittest.TestCase):
    def setUp(self):
        # Set up the WebDriver (e.g., Chrome)
        self.driver = webdriver.Chrome()
        self.driver.maximize_window()
        self.driver.implicitly_wait(10)  # Implicit wait for elements to load

    def test_mensajes_admin(self):
        driver = self.driver
        wait = WebDriverWait(driver, 20)  # Explicit wait

        # Step 1: Open the admin login page
        driver.get("http://your-admin-url.com/login")  # Replace with the actual URL

        # Step 2: Enter username and password
        username_field = driver.find_element(By.ID, "username")  # Replace with actual ID
        password_field = driver.find_element(By.ID, "password")  # Replace with actual ID
        username_field.send_keys("admin")  # Replace with actual username
        password_field.send_keys("password123")  # Replace with actual password
        password_field.send_keys(Keys.RETURN)

        # Step 3: Wait for the code input field to appear
        try:
            code_field = wait.until(EC.presence_of_element_located((By.ID, "code")))  # Replace with actual ID
            code_field.send_keys("123456")  # Replace with the manually provided code
            code_field.send_keys(Keys.RETURN)
        except TimeoutException:
            self.fail("Code input field did not appear in time.")

        # Step 4: Navigate to the "Mensajes" section
        try:
            mensajes_link = wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "Mensajes")))  # Replace with actual text
            mensajes_link.click()
        except TimeoutException:
            self.fail("Mensajes link did not appear in time.")

        # Step 5: Verify that messages are displayed
        try:
            mensajes_table = wait.until(EC.presence_of_element_located((By.ID, "mensajes-table")))  # Replace with actual ID
            self.assertTrue(mensajes_table.is_displayed(), "Mensajes table is not displayed.")
        except TimeoutException:
            self.fail("Mensajes table did not appear in time.")

    def tearDown(self):
        # Close the browser
        self.driver.quit()

if __name__ == "__main__":
    unittest.main()