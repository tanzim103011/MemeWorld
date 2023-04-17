<div class="row post p-1">
	<div class="col-3 bg-light text-center">
		<a href="<?=ROOT?>/profile/<?=$comment->user->id?>">
			<img class="profile-image rounded-circle m-1" src="<?=get_image($comment->user->image ?? '')?>" style="width: 80px;height: 80px;object-fit: cover;">
			<h5><?=esc($comment->user->username ?? 'Unknown')?></h5>
		</a>
	</div>
	<div class="col-9 text-start">
		<div class="muted"><?=get_date($comment->date)?></div>
		<p><?=esc($comment->comment)?></p>

		<?php if(!empty($comment->image)):?>
			<a href="<?=ROOT?>/comment/<?=$comment->id?>">
				<img class="my-1" src="<?=get_image($comment->image)?>" style="width: 100%;">
			</a>
		<?php endif;?>

		<div>
			<?php if(user('id') == $comment->user_id):?>
				<a href="<?=ROOT?>/comment/edit/<?=$comment->id?>">
					<button class="btn-sm m-1 btn btn-warning">Edit</button>
				</a>
				<a href="<?=ROOT?>/comment/delete/<?=$comment->id?>">
					<button class="btn-sm m-1 btn btn-danger">Delete</button>
				</a>
			<?php endif;?>

		</div>
	</div>
 
</div>

