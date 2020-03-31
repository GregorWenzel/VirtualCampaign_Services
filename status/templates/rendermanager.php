<?php include('header.php'); ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Machine name</th>
            <th>Active</th>
            <th>Date</th>
            <th>Status</th>
            <th>Message</th>
            <th>Description</th>
            <th>F.A.</th>
            <th>Actions</th>
        </tr>
        <?php foreach($items as $item): ?>
            <tr>
                <form action="./rendermanager" method="POST">
                    <td class="readonly">
                        <input type="hidden" value="<?php echo $item['id']; ?>" name="id">
                        <input type="hidden" value="<?php echo $_GET['hash']; ?>" name="hash">
                        <?php echo $item['id']; ?>
                    </td>
                    <td>
                        <input class="text-item" type="text" name="machinename" value="<?php echo $item['machinename']; ?>" />
                    </td>
                    <td>
                        <input class="bool-item" type="text" name="is_active" value="<?php echo $item['is_active']; ?>" />
                    </td>
                    <td class="readonly">
                        <?php echo $item['heartbeat_ts']; ?>
                    </td>
                    <td>
                        <input class="bool-item" type="text" name="status" value="<?php echo $item['status']; ?>" />
                    </td>
                    <td>
                        <input style="width: 200px" type="text" name="message" value="<?php echo $item['message']; ?>" />
                    </td>
                    <td>
                        <input style="width: 200px" type="text" name="description" value="<?php echo $item['description']; ?>" />
                    </td>
                    <td>
                        <?php echo $item['force_active']; ?>
                    </td>
                    <td>
                        <input type="submit" name="action" value="Save">
                        <input type="submit" name="action" value="Activate">
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<?php include('footer.php'); ?>