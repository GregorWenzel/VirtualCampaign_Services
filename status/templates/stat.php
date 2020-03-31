<?php include('header.php'); ?>
    <h1>VC Status</h1>
    <h3>VC Manager</h3>
    <table class="status-table">
        <tbody>
        <tr>
            <td>last Update</td>
            <td>
                <?php echo date('d.m.Y - H:i:s', $update); ?>
            </td>
        </tr>
        <tr>
            <td>message</td>
            <td>
                <?php if ($message): ?>
                    <?php echo $message; ?>
                <?php else: ?>
                    none
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>status</td>
            <td>
                <?php if ($status == true): ?>
                    <span class="normal">normal</span>
                <?php else: ?>
                    <span class="unavailable">unavailable</span>
                <?php endif; ?>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="productions-list">
        <h3>Open Productions</h3>
        <table>
            <thead>
            <tr>
                <th>Account</th>
                <th>Production name</th>
                <th>Submitted</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (is_array($openedProductions)): ?>
            <?php foreach ($openedProductions as $item): ?>
                <tr>
                    <td><span><?php echo $item['username']; ?></span></td>
                    <td><span><?php echo $item['name']; ?></span></td>
                    <td>
                        <?php echo date('d.m.Y - H:i', strtotime($item['update_ts'])); ?>
                    </td>
                    <td><?php echo $item['status']; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="productions-list">
        <h3>Finished Productions</h3>
        <table>
            <thead>
            <tr>
                <th>Account</th>
                <th>Production name</th>
                <th>Submitted</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <?php if (is_array($finishedProductions)): ?>
            <?php foreach ($finishedProductions as $item): ?>
                <tr>
                    <td><span><?php echo $item['username']; ?></span></td>
                    <td><span><?php echo $item['name']; ?></span></td>
                    <td>
                        <?php echo date('d.m.Y - H:i', strtotime($item['update_ts'])); ?>
                    </td>
                    <td><?php echo $item['status']; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php include('footer.php'); ?>