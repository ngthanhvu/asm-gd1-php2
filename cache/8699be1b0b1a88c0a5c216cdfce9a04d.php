
<?php $__env->startSection('content'); ?>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<script>Swal.fire('Thành công', '" . $_SESSION['message'] . "', 'success');</script>";
        unset($_SESSION['message']);
    }
    ?>
    <div class="p-3 mb-4 rounded-3 bg-light">
        <h2>Quản lý kích cỡ</h2>
    </div>
    <div class="p-3 mb-4 rounded-3 bg-light">
        <a href="/admin/sizes/create" class="btn btn-primary"><i class="fa-solid fa-circle-plus"></i></a>
        <table class="table table-striped table-bordered table-hover text-center mt-3 rounded-4" style="overflow: hidden">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tên</th>
                    <th scope="col">Hành dộng</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; ?>
                <?php foreach ($sizes as $size) : ?>
                <tr>
                    <th scope="row"><?= $index++ ?></th>
                    <td><?= $size['name'] ?></td>
                    <td>
                        <a href="/admin/sizes/update/<?= $size['id'] ?>" class="btn btn-sm btn-outline-success"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <a href="/admin/sizes/delete/<?= $size['id'] ?>" class="btn btn-sm btn-outline-danger"><i
                                class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($sizes)) : ?>
                <tr>
                    <td colspan="4" class="text-center">Danh sách rỗng!</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\FPOLY\PHP2\asm_gd1\view/admin/sizes/index.blade.php ENDPATH**/ ?>