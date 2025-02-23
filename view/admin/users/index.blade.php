@extends('layouts.admin')
@section('content')
    <div class="p-3 mb-4 rounded-3 bg-light">
        <h2>Quản lý người dùng</h2>
    </div>
    <div class="p-3 mb-4 rounded-3 bg-light">
        <table class="table table-striped table-bordered table-hover text-center mt-3 rounded-4" style="overflow: hidden">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Login with</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $index = 1; ?>
                <?php foreach ($users as $user) : ?>
                <tr>
                    <th scope="row"><?= $index++ ?></th>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['email'] ? $user['email'] : '<span class="badge text-bg-warning">N/A</span>' ?></td>
                    <td><?= $user['role'] ?></td>
                    <td><?= $user['oauth_provider'] ? $user['oauth_provider'] : 'local' ?></td>
                    <td>
                        <a href="/users/update/<?= $user['id'] ?>" class="btn btn-outline-success btn-sm"><i
                                class="fa-solid fa-pen-to-square"></i></a>
                        <a href="/users/delete/<?= $user['id'] ?>" class="btn btn-outline-danger btn-sm"><i
                                class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($users)) : ?>
                <tr>
                    <td colspan="6">No Users</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
@endsection
