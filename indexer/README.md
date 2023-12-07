# Cosmos Inscription Token Name System (CITNS) Indexer

This is a basic CITNS indexer written in PHP which supports indexing CITNS broadcasts, determining statis of transactions, and populating a mysql database with the indexed data.

# DISCLAIMER

`CITNS` is a bleeding-edge experimental protocol to play around with token functionality on **`Cosmos/ATOM`** **`Counterparty/Bitcoin`** and **`Dogeparty/Doge`**. This is a hobby project, and  I am **NOT** responsible for any losses, financial or otherwise, incurred from using this experimental protocol and its functionality. 

Science is messy sometimes... _**DO NOT**_ put in funds your not willing to lose!

# Requires
- PHP 8
- php-bcmath
- MySQL / MariaDB
- counterparty2mysql

# Notes: 
- This indexer requires the usage of [counterparty2mysql](https://github.com/commswap/counterparty2mysql) which pulls Counterparty data into a MySQL database. Counteparty2mysql includes some additional database optimizations, such as indexing all addresses, transactions, and assets for faster queries.
