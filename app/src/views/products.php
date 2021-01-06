<H1><?= $title ?></H1>
<form method="POST" action="/product-stats">
    <select name='currency'>
        <?php foreach ($rates as $currency => $rate) { ?>
            <option value="<?= $currency ?>"><?= $currency ?></option>
        <?php } ?>
    </select>
    <input type="submit" name="select" id="submit">
</form>
<table class="table table-striped">
    <thead>
        <td>Name</td>
        <td>Decription</td>
        <td>Price</td>
        <td>Discount</td>
        <td>Final Price</td>
    </thead>
    <tbody>
        <?php foreach ($stats as $items) {
        ?>
            <tr>
                <td> <?= $items['name'] ?></td>
                <td><?= $items['description'] ?></td>
                <td><?= $items['price'] ?></td>
                <td><?= $items['discount_amount'] ?></td>
                <td><?= $items['final_price'] ?></td>
            </tr>
        <?php
        } ?>
    </tbody>

</table>