1. Enable SQL Server Authentication (If Not Enabled)
By default, SQL Server only allows Windows Authentication. To enable SQL Server Authentication, follow these steps:

Open SQL Server Management Studio (SSMS).

Connect to your SQL Server instance.

In Object Explorer, right-click on the server (e.g., localhost or SQLEXPRESS) → Properties.

Go to the Security tab.

Under Server authentication, select SQL Server and Windows Authentication mode.

Click OK.

Restart SQL Server:

Open SQL Server Configuration Manager.

Go to SQL Server Services → Right-click on SQL Server (MSSQLSERVER) → Restart.

2. Enable 'sa' Account & Reset Password
If you still get the error, the 'sa' account might be disabled. To enable it:

Open SSMS and connect as Windows Authentication.

In Object Explorer, expand Security → Logins.

Right-click on sa → Properties.

In the General tab:

Set a new password for the sa account.

Confirm the password.

In the Status tab:

Set Login to Enabled.

Click OK.

Restart SQL Server.

3. Use insert_admin.php for admin account create