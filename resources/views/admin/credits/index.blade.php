@extends('layouts.admin')
@section('title', 'Credit Manager')

@section('content')
<h1 class="page-header">Credit Manager</h1>

<div class="row">
    <div class="col-sm-5">
        <div class="panel panel-default">
            <div class="panel-heading">Add / Remove Credits</div>
            <div class="panel-body">
                <form method="POST" id="creditForm">
                    @csrf
                    <div class="form-group">
                        <label>Account Username</label>
                        <input type="text" class="form-control" name="account_username" id="accountInput" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <label>Credit Config</label>
                        <select class="form-control" name="config_id" required>
                            @foreach($configs as $cfg)
                            <option value="{{ $cfg->config_id }}">{{ $cfg->config_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" name="amount" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-success" formaction="{{ url('admin/credits/__ACCOUNT__/add') }}" onclick="setAction(this,'add')">Add</button>
                    <button type="submit" class="btn btn-danger" formaction="{{ url('admin/credits/__ACCOUNT__/remove') }}" onclick="setAction(this,'remove')">Remove</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="panel panel-default">
            <div class="panel-heading">Credit Configurations</div>
            <table class="table table-condensed" style="margin:0;">
                <thead><tr><th>Title</th><th>Database</th><th>Table</th><th>Col</th><th>Display</th></tr></thead>
                <tbody>
                    @foreach($configs as $cfg)
                    <tr>
                        <td>{{ $cfg->config_title }}</td>
                        <td>{{ $cfg->config_database }}</td>
                        <td>{{ $cfg->config_table }}</td>
                        <td>{{ $cfg->config_credits_col }}</td>
                        <td>{{ $cfg->config_display ? 'Yes' : 'No' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function setAction(btn, type) {
    var account = document.getElementById('accountInput').value;
    btn.form.action = '/admin/credits/' + encodeURIComponent(account) + '/' + type;
}
</script>
@endsection
