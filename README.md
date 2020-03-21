PHP Mysql Restore Tool
========
通过php脚本执行mysql restore的操作

# Why
1. 数据库迁移过程中由于无法登陆到`mysql server`上执行命令:
  ```bash
  mysql -uroot -p dbname < bakfile 
  ```
  于是考虑用代码来实现导入！

# 安装使用
  1. git clone 本项目
  2. cd php-sql-restore
  3. 修改数据库配置文件
  4. 执行 `php -f restore.php`

