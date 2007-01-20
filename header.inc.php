<?php 
include_once('./include/include_main.php');
echo <<<EOD
<title>Simple Invoices</title>
<link rel="stylesheet" type="text/css" href="header1.css" media="all"/>
<link rel="stylesheet" type="text/css" href="header2.css" media="all"/>
<link rel="stylesheet" type="text/css" href="screen.css" media="all"/>
<link rel="stylesheet" type="text/css" href="print.css" media="print"/>
<link rel="stylesheet" type="text/css" href="blue.css" media="screen"/>
<!--[if gte IE 5.5]>
<script language="JavaScript" src="dhtml.js" type="text/JavaScript"></script>
<![endif]-->
EOD;

echo <<<EOD
<div id="Header">
      <div id="Tabs">
        <ul id="MainTabs">


<ul id="navmenu">
  <li><a href="index.php">Home</a></li>
  <li><a href="index.php?module=invoices&view=manage">Invoices +</a>
	<ul>
		<li><a href="index.php?module=invoices&view=manage">Manage Invoices</a></li>
		<li></li>
		<li><a href="index.php?module=invoices&view=total">New Invoice - Total</a></li>
		<li><a href="index.php?module=invoices&view=itemised">New Invoice - Itemised</a></li>
		<li><a href="index.php?module=invoices&view=consulting">New Invoice - Consulting</a></li>
	</ul>
 </li>
  <li><a href="index.php?module=customer&view=manage">Customers +</a>
	<ul>
		<li><a href="index.php?module=customers&invoices&view=manage">Manage Customers</a></li>
		<li><a href="index.php?module=customers&view=add">Add Customer</a></li>
	</ul>
 </li>
  <li><a href="index.php?module=products&view=manage">Products +</a>
	<ul>
		<li><a href="index.php?module=products&view=manage">Manage Products</a></li>
		<li><a href="index.php?module=products&view=add">Add Product</a></li>
	</ul>
 </li>
  <li><a href="index.php?module=billers&view=manage">Billers +</a>
	<ul>
		<li><a href="index.php?module=billers&view=manage">Manage Billers</a></li>
		<li><a href="index.php?module=billers&view=add">Add Billers</a></li>
	</ul>
 </li>
  <li><a href="index.php?module=payments&view=manage">Payments +</a>
	<ul>
		<li><a href="index.php?module=payments&view=manage">Manage Payments</a></li>
		<li><a href="index.php?module=payments&view=process&op=pay_invoice">Process Payment</a></li>
	</ul>
 </li>
  <li><a href="index.php">Reports +</a>
	<ul>
		<li><a href="index.php?module=reports&view=report_sales_total">Sales +</a>
			<ul>
				<li><a href="index.php?module=reports&view=report_sales_total">Total Sales</a></li>
			</ul>
		</li>
		<li><a href="index.php?module=reports&view=report_sales_customers_total">Sales by customers +</a>
			<ul>
				<li><a href="./index.php?module=reports&view=report_sales_customers_total">Total Sales by Customer</a></li>
			</ul>
		</li>
		<li><a href="./index.php?module=reports&view=report_tax_total">Tax +</a>
			<ul>
				<li><a href="./index.php?module=reports&view=report_tax_total">Total taxes</a></li>
			</ul>
		</li>
		<li><a href="index.php?module=reports&view=report_products_sold_total">Product sales +</a>
			<ul>
				<li><a href="./index.php?module=reports&view=report_products_sold_total">Products sold - total</a></li>
			</ul>
		</li>
		<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">Products by customer +</a>
			<ul>
				<li><a href="./index.php?module=reports&view=report_products_sold_by_customer">Products sold - Customer - Total</a></li>
			</ul>
		</li>
		<li><a href="index.php?module=reports&view=report_biller_total">Biller sales +</a>
			<ul>
				<li><a href="index.php?module=reports&view=report_biller_total">Biller sales - Total</a></li>
				<li><a href="./index.php?module=reports&view=report_biller_by_customer">Biller sales by Customer - Totals</a></li>
			</ul>
		</li>
		<li><a href="./index.php?module=reports&view=report_debtors_by_amount">Debtors +</a>
			<ul>
				<li><a href="./index.php?module=reports&view=report_debtors_by_amount">Debtors by amount owed</a></li>
				<li><a href="./index.php?module=reports&view=report_debtors_by_aging">Debtors by Aging periods</a></li>
				<li><a href="./index.php?module=reports&view=report_debtors_owing_by_customer">Total owed per customer</a></li>
				<li><a href="./index.php?module=reports&view=report_debtors_aging_total">Total by Aging periods</a></li>
			</ul>
		</li>
	</ul>
 </li>

<li><a href="#">Options +</a>
    <ul>
	<li>
		<a href="./index.php?module=system_defaults&view=manage">System Defaults</a>
	</li>
	<li>
		<a href="./index.php?module=custom_fields&view=manage">Custom Fields</a>
	</li>
	<li></li>
        <li>
		<a href="./index.php?module=tax_rates&view=manage">Tax Rates +</a>
		<ul>
			 <li>
				<a href="./index.php?module=tax_rates&view=manage">Manage Tax Rates</a>
			 </li>
		         <li>
				<a href="./index.php?module=tax_rates&view=add">Add Custom Tax Rate</a>
			</li>
	        </ul>
        </li>
        <li>
		<a href="./index.php?module=preferences&view=manage">Invoice Preferences +</a>
		<ul>
			<li>
				<a href="./index.php?module=preferences&view=manage">Manage Invoice Preferences</a>
			</li>
		        <li>
				<a href="./index.php?module=preferences&view=add">Add New Invoice Preferences</a>
			</li>
	        </ul>
        </li>
        <li>
		<a href="./index.php?module=payment_types&view=manage">Payment Types +</a>
         	<ul>
			<li>
				<a href="./index.php?module=payment_types&view=manage">Manage ayment Types</a>
			</li>
		        <li>
				<a href="./index.php?module=payment_types&view=add">Add New Payment Types</a>
			</li>
          	</ul>
        </li>
	<li></li>
	<li>
		<a href="./index.php?module=options&view=database_sqlpatches">Database Upgrade Manager</a>
	</li>
        <li>
		<a href="./index.php?module=options&view=backup_database">Backup Database</a>
	</li>
        <li>
		<a href="./index.php?module=options&view=sanity_check">Sanity check of invoices</a>
	</li>
	<li></li>
        <li>
		<a href="index.php?module=documentation/inline_docs&view=inline_instructions">Help +</a>
         	<ul>
			<li>
				<a href="index.php?module=documentation/inline_docs&view=inline_instructions#installation">Installation</a>
			</li>
			<li>
				<a href="index.php?module=documentation/inline_docs&view=inline_instructions#upgrading">Upgrading Simple Invoices</a>
			</li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#prepare">Prepare Simple Invoices for use</a></li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#use">Using Simple Invoices</a></li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_instructions#faqs">Frequently Asked Questions</a></li>
           <li><a href="index.php?module=options&view=help">Get Help</a></li>
          </ul>
        </li>
	<li><a href="index.php?module=documentation/inline_docs&view=about">About +</a>
         <ul>
           <li><a href="index.php?module=documentation/inline_docs&view=about">About</a></li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_changelog">Change Log</a></li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_credits">Credits</a></li>
           <li><a href="index.php?module=documentation/inline_docs&view=inline_license">License</a></li>
          </ul>
        </li>
	

     </ul>
   </li>
   <li><a href="login.php">Login/logout</a></li>
 </ul>
</div id="Tabs">
</div id="Header">

  <div id="Wrapper">
         <div id="Container">
 
 <div class="Full">
   <div class="col">
EOD;