<?php $this->view('header')?>
	
	<div class="p-2 col-md-6 shadow mx-auto border rounded">

		<div class="text-center"> 
			
			<center><h5>Profile Settings</h5></center>
			<span>
				<img class="profile-image rounded-circle m-4" src="<?=get_image($row->image)?>" style="width: 200px;height: 200px;object-fit: cover;">
				
				<?php if(user('id') == $row->id):?>
					<label>
						<i style="position: absolute;cursor: pointer;" class="h1 text-primary bi bi-image"></i>
						<input onchange="display_image(this.files[0])" type="file" class="d-none" name="">
					</label>
				<?php endif;?>

			</span>
			<div class="profile-image-prog progress d-none">
			  <div class="progress-bar" style="width: 0%;" >0%</div>
			</div>

			<form method="post" onsubmit="submit_post(event)">
				<input value="<?=old_value('username',$row->username)?>" type="text" class="form-control" id="username" placeholder="Username">
				<input value="<?=old_value('email',$row->email)?>" type="text" class="form-control" id="email" placeholder="Email">
				<input value="<?=old_value('password','')?>" type="text" class="form-control" id="password" placeholder="Password (leave empty to keep old one)">
			
				<button class="btn btn-warning m-2">Save</button>
			</form>
			<div class="post-prog progress d-none">
			  <div class="progress-bar" style="width: 0%;" >0%</div>
			</div>
			<script>
				
				function display_image(file)
				{
					let allowed = ['jpg','jpeg','png','webp'];
					let ext = file.name.split(".").pop();

					if(!allowed.includes(ext.toLowerCase()))
					{
						alert('Only files of this type allowed: '+ allowed.toString(", "));
						return;
					}

					document.querySelector(".profile-image").src = URL.createObjectURL(file);
					change_image(file);
				}
 
			</script>
		</div>
 
	</div>

	<script>
		
		var post_image_added = false;

		function change_image(file)
		{
			var obj = {};
			obj.image = file;
			obj.data_type = "profile-image";
			obj.id = "<?=user('id')?>";
			obj.progressbar = 'profile-image-prog';
			send_data(obj);
		}

		function submit_post(e)
		{
			e.preventDefault();

			var obj = {};
 
			obj.username = e.currentTarget.querySelector("#username").value;
			obj.email = e.currentTarget.querySelector("#email").value;
			obj.password = e.currentTarget.querySelector("#password").value;
			obj.data_type = "profile-settings";
			obj.id = "<?=user('id')?>";
			obj.progressbar = 'post-prog';
			send_data(obj);
		}

		function send_data(obj)
		{

			var myform = new FormData();
			var progressbar = null;

			if(typeof obj.progressbar != 'undefined')
				progressbar = document.querySelector("."+obj.progressbar);

			for(key in obj)
			{
				myform.append(key,obj[key]);
			}

			var ajax = new XMLHttpRequest();

			ajax.addEventListener('readystatechange',function(e){

				if(ajax.readyState == 4 && ajax.status == 200)
				{
					handle_result(ajax.responseText);
				}
			});

			if(progressbar)
			{	
				progressbar.classList.remove("d-none");
				progressbar.children[0].style.width = "0%";
				progressbar.children[0].innerHTML = "0%";

				ajax.upload.addEventListener('progress',function(e){

					let percent = Math.round((e.loaded / e.total) * 100);
					progressbar.children[0].style.width = percent + "%";
					progressbar.children[0].innerHTML = percent + "%";
				});
			}

			ajax.open('post','<?=ROOT?>/ajax',true);
			ajax.send(myform);

		}

		function handle_result(result)
		{
			console.log(result);
			let obj = JSON.parse(result);

			alert(obj.message);

			if(obj.success)
				window.location.reload();
		}

	</script>
<?php $this->view('footer')?>