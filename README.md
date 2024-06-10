# **Document Management**
This project is a PHP-based document management system that allows users to store, organize, and retrieve documents. The application is hosted on a virtual machine with an Ubuntu operating system, running on VirtualBox.

# **Installation**
To install this project, follow these steps:

1.Clone the repository to your local machine.
2.Set up a VirtualBox instance with Ubuntu and configure it to run PHP.
3.Install the necessary dependencies, such as the PHP cURL extension.
4.Set up a MySQL database and configure the application to use it.
5.Set up the cronjob to run the script that requests documents from the external API every hour.

# **Features**
<br>Upload and store documents securely.
<br>Organize documents into categories and subcategories.
<br>Retrieve documents based on various criteria, such as file name or category.
<br>Access control for users and groups.
<br>Automatic logging and reporting of all requests made to the external API.

# **Security**
The application is built with security in mind and implements various security measures, such as:
<br>Input validation to prevent SQL injection and other attacks.

# **Limitations**
The application is limited by the resources of the virtual machine it is hosted on. The current setup includes a cronjob that requests documents from an external API every hour. This may be limited by the API's rate limits or availability.

# **Contributing**
Contributions to this project are welcome! To contribute, follow these steps:

<br>Fork the repository
<br>Make your changes.
<br>Submit a pull request.
