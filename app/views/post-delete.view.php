<?php $this->view('header')?>
  
  <div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

    	<?php if(!empty($post)):?>
    	<form method="post" onsubmit="submit_post(event)">
		<div class="row post p-1">
			<div class="m-1 alert alert-danger text-center">Are you sure you want to delete this post?!</div>
			<div class="row post p-1">
				<div class="col-3 bg-light text-center">
					<a href="<?=ROOT?>/profile/<?=$post->user->id?>">
						<img class="profile-image rounded-circle m-1" src="<?=get_image($post->user->image ?? '')?>" style="width: 80px;height: 80px;object-fit: cover;">
						<h5><?=esc($post->user->username ?? 'Unknown')?></h5>
					</a>
				</div>
				<div class="col-9 text-start">
					<div class="muted"><?=get_date($post->date)?></div>
					<p><?=esc($post->post)?></p>

					<?php if(!empty($post->image)):?>
						<a href="<?=ROOT?>/post/<?=$post->id?>">
							<img class="my-1" src="<?=get_image($post->image)?>" style="width: 100%;">
						</a>
					<?php endif;?>
					<input type="hidden" id="post_id" value="<?=$post->id?>">
					<div>
						<?php if(user('id') == $post->user_id):?>
							<a href="<?=ROOT?>/post/<?=$post->id?>">
								<button type="button" class="btn-sm m-1 btn btn-secondary">Back</button>
							</a>
							
							<button class="btn-sm m-1 btn btn-danger float-end">Delete</button>
						<?php endif;?>

					</div>
				</div>

			</div>

		</div>
		</form>
		<?php else:?>
			<div class="m-1 alert alert-danger text-center">Sorry! That record was not found!</div>
		<?php endif;?>
		
		<div class="post-prog progress d-none">
		  <div class="progress-bar" style="width: 0%;" >0%</div>
		</div>
	</div>
  </div>

	<script>
		
		function submit_post(e)
		{
			e.preventDefault();

			var obj = {};
			obj.post_id = e.currentTarget.querySelector("#post_id").value;
			obj.data_type = "delete-post";
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
			//console.log(result);
			let obj = JSON.parse(result);

			alert(obj.message);

			if(obj.success)
				window.location.href = '<?=ROOT?>/profile';
		}

	</script>

<?php $this->view('footer')?>