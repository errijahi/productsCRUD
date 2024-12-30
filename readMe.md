# Web Application Documentation

## Overview
This web application allows users to perform CRUD operations (Create, Read, Update, Delete) for products, including their quantity and price. It also displays the data in a table and performs additional calculations.

## Technologies Used
- **PHP Version**: 8.2.4
- **Frontend**: Bootstrap
- **Development Environment**: XAMPP
- **Data Storage**: `storage.json`

## Features
1. **CRUD Operations**:
   - Create new product entries.
   - View the list of products.
   - Update existing product details (quantity and price).
   - Delete products.

2. **Dynamic Table**:
   - Displays product data.
   - Includes additional calculated values based on product details.

## Data Storage
All data is stored in a JSON file named `storage.json`.

### Important Note
To enable data storage in `storage.json`, ensure that the file has the appropriate write permissions. Without write permissions, the application will not be able to save updates to the data.

### Setting Write Permissions
1. Locate the `storage.json` file in your project directory.
2. Set the file permissions to allow writing. For example:
   - On Linux/Unix:
     ```bash
     chmod 666 storage.json
     ```
   - On Windows:
     - Right-click on the file, select **Properties**.
     - Navigate to the **Security** tab and edit permissions to allow write access for the desired user or group.

## Prerequisites
- XAMPP installed and configured on your system.
- PHP 8.2.4 or later.
- Bootstrap included for styling.
- `storage.json` file present in the project root directory with the appropriate write permissions.

---
For additional assistance, consult the application documentation or contact support.

