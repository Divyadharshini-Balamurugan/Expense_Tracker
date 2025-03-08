# 💰 Expense Tracker

## 📌 Overview
The **Expense Tracker** is a web-based application built using **PHP** and **MySQL** that helps users **log daily expenses manually**. Users can visualize their **monthly and yearly spending patterns** using **pie charts** and generate **PDF or Excel reports** for selected date ranges.

---

## ✨ Features
✅ **Manual Expense Entry** – Easily add and manage daily expenses.  
✅ **Visual Insights** – Track spending patterns with **monthly & yearly pie charts**.  
✅ **Secure Authentication** – User login and password management.  
✅ **Database Storage** – Uses **MySQL** for structured expense management.  
✅ **PDF & Excel Export** – Download reports in **PDF or Excel format**.  
✅ **Change Password** – Secure account management.

---

## 🛠 Tech Stack
🖥 **Backend**: PHP  
📄 **Frontend**: HTML, CSS, JavaScript, Bootstrap  
💾 **Database**: MySQL  
📊 **Charts**: Chart.js  
📄 **Report Generation**: TCPDF (for PDF), PHPExcel (for Excel)  

---

## ⚙️ Installation & Setup
1. **Clone the repository**
   ```sh
   git clone https://github.com/your-repo/expense-tracker.git
   cd expense-tracker
   ```

2. **Setup MySQL Database**
   - Open MySQL and create a database:
     ```sql
     CREATE DATABASE dailyexpense;
     USE dailyexpense;
     ```
   - Import the provided `dailyexpense.sql` file into MySQL.
   - Ensure your database name is **`dailyexpense`** to match the configuration in `config.php`.

3. **Configure Database Connection**
   - Open `config.php` and ensure your database credentials match:
     ```php
     $con = mysqli_connect("localhost", "root", "", "dailyexpense");
     ```
   - If using a different MySQL user/password, update `config.php` accordingly.

4. **Run the Application**
   - Place the project folder inside your web server directory (e.g., `htdocs` for XAMPP).
   - Start Apache and MySQL using XAMPP or any preferred server.
   - Access the app via `http://localhost/expense-tracker/`

---

## 🗄 Database Schema
### **Table: `users`**
```sql
CREATE TABLE users (
  user_id INT(11) PRIMARY KEY AUTO_INCREMENT,
  firstname VARCHAR(50) NOT NULL,
  lastname VARCHAR(25) NOT NULL,
  email VARCHAR(50) NOT NULL UNIQUE,
  profile_path VARCHAR(50) NOT NULL DEFAULT 'default_profile.png',
  password VARCHAR(255) NOT NULL,
  trn_date DATETIME NOT NULL
);
```

### **Table: `expenses`**
```sql
CREATE TABLE expenses (
  expense_id INT(20) PRIMARY KEY AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  expense DECIMAL(10,2) NOT NULL,
  expensedate DATE NOT NULL,
  expensecategory VARCHAR(50) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);
```

---

## 🔗 Key PHP Files & Functionality
- **`index.php`** – User login page  
- **`add_expense.php`** – Add new expense entry  
- **`fetch_expenses.php`** – Retrieve expenses from the database  
- **`export.php`** – Export data in Excel format  
- **`excel_export.php`** – Generate an Excel report  
- **`change_password.php`** – User password management  
- **`config.php`** – Database configuration  

---

## 🎯 How to Use
1. **🔐 Login/Register** – Securely access your expense tracker.  
2. **✍️ Add Expenses** – Manually log your daily expenses.  
3. **📊 View Reports** – Analyze spending with **interactive pie charts**.  
4. **📄 Generate Reports** – Download expense reports in **PDF or Excel**.  

---

## 🚀 Future Enhancements
🔹 **Automate recurring expenses**  
🔹 **AI-based expense categorization**  
🔹 **Budget setting & alerts**  

---

## 📜 License
This project is open-source under the **MIT License**.  

💡 *Contribute to make this even better! Happy Tracking!* 🎉

