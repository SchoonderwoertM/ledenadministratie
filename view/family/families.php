<h1>Families</h1>

<table>
    <thead>
        <th>Families</th>
    </thead>
    <tbody>
        <?php foreach ($families as $family) { ?>
            <tr>
                <td><?php echo $family['Name']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
