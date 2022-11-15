
<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP Insert Update Delete with Vue.js</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
 </head>
 <body>
  <div class="container mt-5" id="crudApp">
   <br />
   <h3 align="center">CRUD APP using VueJS & PHP</h3>
   <hr>
   <br />
     <div class="row">
      <div class="col-md-6">
       <h3 class="panel-title">Users Data</h3>
      </div>
      <div class="col-md-6" align="right">
       <input type="button" class="btn btn-success btn-xs" data-bs-toggle="modal" data-bs-target="#myModal" @click="openModel" value="Add" />
      </div>
     </div>
     <div class="table-responsive">
      <table class="table table-bordered table-striped">
       <tr>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Actions</th>
       </tr>
       <tr v-for="row in allData">
        <td>{{ row.first_name }}</td>
        <td>{{ row.last_name }}</td>
        <td>{{ row.email }}</td>
        <td><button type="button" name="edit" class="btn btn-primary btn-xs edit" data-bs-toggle="modal" data-bs-target="#myModal" @click="fetchData(row.id)">Edit</button>
        <button type="button" name="delete" class="btn btn-danger btn-xs delete" @click="deleteData(row.id)">Delete</button></td>
       </tr>
      </table>
     </div>

   
   <div v-if="myModel" id="myModal" class="modal fade" tabindex="-1">
       <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header">
          <h4 class="modal-title">{{ dynamicTitle }}</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="myModel=false"></button>
         </div>
         <div class="modal-body">
          <div class="form-group">
           <label>Enter First Name</label>
           <input type="text" class="form-control" v-model="first_name" />
          </div>
          <div class="form-group">
           <label>Enter Last Name</label>
           <input type="text" class="form-control" v-model="last_name" />
          </div>
          <div class="form-group">
           <label>Enter Email</label>
           <input type="email" class="form-control" v-model="email" />
          </div>
          <br />
          <div class="modal-footer">
            <input type="hidden" v-model="hiddenId" />
            <input type="button" class="btn btn-success btn-xs" v-model="actionButton" @click="submitData" />
        </div>
         </div>
        </div>
       </div>
   </div>
  </div>
 </body>
</html>

<script>

let app = new Vue({
 el:'#crudApp',
 data:{
  allData:'',
  myModel:false,
  hiddenId: null,
  actionButton:'Insert',
  dynamicTitle:'Add Data',
 },
 methods:{
  fetchAllData(){
   axios.post('action.php', {
    action:'fetchall'
   }).then(res => {
    app.allData = res.data;
   });
  },
  openModel(){
   app.first_name = '';
   app.last_name = '';
   app.email = '';
   app.actionButton = "Insert";
   app.dynamicTitle = "Add Data";
   app.myModel = true;
  },
  submitData(){
   if(app.first_name != '' && app.last_name != '' &&  app.email != '') {
    if(app.actionButton == 'Insert')
    {
     axios.post('action.php', {
      action:'insert',
      firstName:app.first_name, 
      lastName:app.last_name,
      email:app.email
     }).then(function(response){
      app.myModel = false;
      app.fetchAllData();
      app.first_name = '';
      app.last_name = '';
      app.email = '';
      alert(response.data.message);
      window.location.reload(); 
     });
    }
    if(app.actionButton == 'Update')
    {
     axios.post('action.php', {
      action:'update',
      firstName : app.first_name,
      lastName : app.last_name,
      email : app.email,
      hiddenId : app.hiddenId
     }).then(res => {
      app.myModel = false;
      app.fetchAllData();
      app.first_name = '';
      app.last_name = '';
      app.email = '';
      app.hiddenId = '';
      alert(res.data.message)
      window.location.reload(); 
     });
    }
   } else {
    alert("Fill All Field");
   }
  },
  fetchData(id){
   axios.post('action.php', {
    action:'fetchSingle',
    id:id
   }).then(res => {
    app.first_name = res.data.first_name;
    app.last_name = res.data.last_name;
    app.email = res.data.email;
    app.hiddenId = res.data.id;
    app.myModel = true;
    app.actionButton = 'Update';
    app.dynamicTitle = 'Edit Data';
   });
  },
  deleteData(id){
   if(confirm("Are you sure you want to remove this data?"))
   {
    axios.post('action.php', {
     action:'delete',
     id:id
    }).then(res => {
     app.fetchAllData();
     alert(res.data.message);
    });
   }
  }
 },
 created(){
  this.fetchAllData();
 }
});
</script>
