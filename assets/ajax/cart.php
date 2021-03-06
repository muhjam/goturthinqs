<?php 
session_start();
require '../php/functions.php';

if(isset($_COOKIE['code'])){
	$code_hash=$_COOKIE['code'];
} 

?>
<?php if(!isset($_GET['undoCart'])&&!isset($_GET['cleanCart'])): ?>
<style>
.alert-success {
	background-color: #0f834d;
	font-size: 16px;
	padding: 10px 20px;
	width: 90%;
	margin: -25px auto 15px auto;
	color: white;
	border-radius: 5px;
	align-items: center;
	animation: slideRight 0.5s;
	display: flex;
}

.alert-success .alert-logo {
	margin-right: 20px;
}

.alert-success .alert-logo svg path {
	color: white;
}

.alert-success .alert-text {
	color: white;
}

.alert-success .alert-text a {
	color: white;
}

.alert-success .alert-text a:hover {
	color: #ddd;
	text-decoration: none;
}

@keyframes slideRight {
	0% {
		transform: translateX(0%)
	}

	30% {
		transform: translateX(10%)
	}
}
</style>
<div class="alert-success">
	<div class="alert-logo">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill"
			viewBox="0 0 16 16">
			<path
				d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
		</svg>
	</div>
	<div class="alert-text">"<?= $_SESSION['nameRemove'];?>" removed. <a href="#" onclick="undo()">Undo?</a></div>
</div>
<?php endif; ?>
<?php if(isset($_COOKIE['shopping_cart'])&&isset($_COOKIE['code'])&&$code_hash===hash('sha256', $_COOKIE['shopping_cart'])&&strlen($_COOKIE['shopping_cart'])>16): ?>
<div class="action">
	<button type="button" onclick="clearCart()">CLEAR</button>
	<form>
		<input type="text" placeholder="Voucher Code" name="voucher" maxlength="10" autocomplete="off">
		<button>USE</button>
	</form>
</div>
<?php endif; ?>
<style>
.container {
	animation: slideUp 0.5s;
}

@keyframes slideUp {
	0% {
		transform: translatey(0%)
	}

	30% {
		transform: translatey(10%)
	}
}
</style>
<div class="container">
	<div class="content">
		<?php if(isset($_COOKIE['shopping_cart'])&&isset($_COOKIE['code'])&&$code_hash===hash('sha256', $_COOKIE['shopping_cart'])&&strlen($_COOKIE['shopping_cart'])>16): ?>
		<?php 
		$total=0; 
		$cookie_data=stripcslashes($_COOKIE['shopping_cart']);
		$cart_data=json_decode($cookie_data, true);
		?>
		<table>
			<?php foreach($cart_data as $keys => $product): ?>
			<tr class="tr-product">
				<td><img src="assets/img/<?= $product['image'];?>" width="100px" height="100px"></td>
				<td><strong><a href="product.php?number=<?= $product['id'];?>"><?= $product['name']; ?></a></strong>
					<br>
					<i><?= $product['code']; ?></i>
					<br>
					Type - <strong><?= $product['type']; ?></strong>
					<br>
					Size - <strong><?= $product['size']; ?></strong>
					<br>
					<span id="price-mobile">Price - <strong><?= idr($product['price']);?></strong></span>
					<br>
					<a id="remove-mobile" onclick="removeCart(<?= $product['id'];?>)">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash"
							viewBox="0 0 16 16">
							<path
								d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
							<path fill-rule="evenodd"
								d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
						</svg>
					</a>
				</td>

				<td id="remove-desk"><a onclick="removeCart(<?= $product['id'];?>)">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash"
							viewBox="0 0 16 16">
							<path
								d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
							<path fill-rule="evenodd"
								d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
						</svg>
					</a></td>
				<td id="price-desk"><?= idr($product['price']);?></td>
			</tr>
			<?php $total=$total + $product['price']; ?>
			<?php endforeach; ?>
		</table>
	</div>
	<div class="summary">
		<p>TOTAL :</p>
		<p><?= rupiah($total); ?></p>
		<button>Check Out</button>
	</div>
	<?php else: ?>
</div>
<style>
.container .content {
	overflow: none;
	width: auto;
	height: auto;
}
</style>
<div style="padding:0 0 200px 0;text-align:center;">
	<h1
		style="color:#a9a9a9;font-family: 'Open Sans', sans-serif;margin-top:120px;text-align:center;text-transform:uppercase;font-weight:400;">
		ITMES SHOPPING CART IS EMPTY</h1>
</div>
<?php endif; ?>