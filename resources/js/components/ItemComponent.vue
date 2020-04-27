<template>
<div>
    <button @click="listModal" class="btn btn-primary btn-block" >Add to your list</button>
    <table class="table" >
        <thead>
            <tr>
                <th>id</th>
                <th>name</th>
                <th>description</th>
            </tr>
        </thead>

        <tbody>
            <tr v-for="(item, index) in items">
                <td>{{index +1 }}</td>
                <td>{{item.name}}</td>
                <td>{{item.description}}</td>
                <td><button @click="showUpdateModal(index)" class="btn btn-info">Edit </button></td>
                <td><button @click="deleteItem(index)" class="btn btn-danger">Delete  </button></td>
            </tr>
        </tbody>
    </table>

    <div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Add to your List</h5>
        <!-- <button type="button" class="cloexampleModalLongTitlese" data-dismiss="modal" aria-label="Close"> -->
          <!-- <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
          <div class="alert alert-danger" v-if="errors.length > 0 " >
              <ul>
                  <li v-for="error in errors"> {{error}}</li>
              </ul>
          </div>

          <div class="form-group" >
                <label for="name" >Name </label>
                <input v-model="item.name" type="text" id="name" class="from-control" >
          </div>
          <div class="form-group" >
                <label for="description" >Description </label>
                <input v-model="item.description" type="text" id="description" class="from-control" >
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button @click="createItem" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="">Update your list</h5>
        <!-- <button type="button" class="cloexampleModalLongTitlese" data-dismiss="modal" aria-label="Close"> -->
          <!-- <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <div class="modal-body">
          <div class="alert alert-danger" v-if="errors.length > 0 " >
              <ul>
                  <li v-for="error in errors"> {{error}}</li>
              </ul>
          </div>

          <div class="form-group" >
                <label for="name" >Name </label>
                <input v-model="updateItem.name" type="text" id="name" class="from-control" >
          </div>
          <div class="form-group" >
                <label for="description" >Description </label>
                <input v-model="updateItem.description" type="text" id="description" class="from-control" >
          </div>
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button @click="editItem" type="button" class="btn btn-primary">update</button>
      </div>
    </div>
  </div>
</div>
</div>
</template>

<script>
    export default {
        data(){
            return{
                item:{
                    name:'',
                    description:''
                },
                items:[],
                uri: 'http://localhost:8000/items/',
                errors:[],
                updateItem:[],
            }
        },
        methods:{
            listModal(){
                $("#addListModal").modal("show");
            },
            showUpdateModal(index){
                $("#updateModal").modal("show");
                this.updateItem = this.items[index];
            },
            
            loadItems(){
             // make call to get all items
            axios.get(this.uri).then((response) => {
                console.log(response.data.data);
                this.items = response.data.items;
            }).catch(error=>{
                console.log(error);
            });
            },
            editItem(){
                axios.patch(this.uri + this.updateItem.id, {
                    name: this.updateItem.name,
                    description: this.updateItem.description,
                })
                .then(response=>{
                    $("#updateModal").modal("hide");

                })
                .catch(error=>{
                    if(error.response.data.errors.name){
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if(error.response.data.errors.body){
                        this.errors.push(error.response.data.errors.body[0]);
                    }
                });

            },
            deleteItem(index){
                axios.delete(this.uri + this.items[index].id)
                .then(response=>{
                    this.$delete(this.items, index);
                })
                .catch(error=>{
                    console.log('could not delete');
                });
            },

            createItem(){
                axios.post(this.uri, {name:this.item.name, description:this.item.description})
                .then(response=>{
                    this.items.push(response.data.item);
                     $("#addListModal").modal("hide");
                })
                .catch(error=>{
                    this.errors = [];
                    if(error.response.data.errors.name){
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                    if(error.response.data.errors.body){
                        this.errors.push(errors.response.data.errors.body[0]);
                    }
                });

            },
            resetData(){
                this.items.name = '';
                this.items.description = '';
            },

        },

        mounted() {
            this.loadItems();
            console.log('Component mounted.')
        }
    }
</script>
