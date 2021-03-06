<?php 
	
	$listUserFavorites  =  $listUserFavorites;
	$userSession = $_SESSION['acl']['account'];
?>
<section class="light_section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-4 profile-avatar">
                        <div class="text-center">
                            <img id="product-image" src="<?php echo $user->getLinkAvatar();?>" >
                        </div>
                        <button idUser="<?php echo $user->getId()?>" class="btn <?php echo ( $is_favorite == true ) ? 'unfavorite btn-danger' : 'add-favorite btn-info' ?>"><?php echo ( $is_favorite == true ) ? 'Unfavorite' : 'Add Favorite' ?></button>
                        <button idUser="<?php echo $user->getId()?>" class="btn <?php echo ( $is_follow == true ) ? 'un-follow btn-danger' : 'add-follow btn-info' ?>"><?php echo ( $is_follow == true ) ? 'Unfollow' : 'Add Follow' ?></button>
                        
                        <?php if( $user->getStatusForUserSession() == 0 ):?>
                        	<?php 
	                        	/**
	                        	 * 0 : add friend
	                        	 * 1 : unfriend
	                        	 * 2 : unRequest
	                        	 * @var int  */
                        	?>
	                        		
                        				<button class="btn btn-info">
                        					<a idfriend="<?php echo $user->getId()?>" style="color: white;" class="add-friend" href="#">
                        						Add Friend
                        					</a>
                        				</button>
                        		
                        	<?php elseif ( $user->getStatusForUserSession() == 1 ):?>
                        		
                        			<button  class="btn btn-danger">
                        				<a style="color: white;" idfriend="<?php echo $user->getId()?>" class="un-friend" href="#">
                        					UnFriend
                        				</a>
                        			</button>
                        		
                        	<?php elseif ( $user->getStatusForUserSession() == 2 ):?>
                        	
                        			<button  class="btn btn-danger">
                        				<a style="color: white;" idfriend="<?php echo $user->getId()?>" class="un-request" href="#">
                        					unRequest
                        				</a>
                        			</button>
                        	
                        	<?php endif;?>
                        
                    </div>
                    <div class="col-sm-8 single-product-description">
                        <div class="product-prices">
                            <div class="row">
                                <div class="col-xs-12">
                                    <span class="product-vars">
                                    	<input style="display: none" type="text" value="" name="fullname" class="form-control" placeholder=""> 
                                    	<h1>
                                    		<strong>
                                    			<span>
	                                    			<?php echo htmlspecialchars( $user->getFullname() )?>
                                    			</span>
	                                    	</strong>
                                    	</h1>
                                    </span>
                                </div>
                            </div>
                        </div>

						<div class="row">
							
								<div class="col-xs-12">
									<span class="product-vars">
	                            		<strong>
	                                		Email :
	                            		</strong>
                            			
                            			<span><?php echo $user->getEmail()?></span>
                            			<input style="display: none" type="text" value="" name="email" class="form-control" placeholder="">
	                       		 	</span>
								</div>
								 
                             </span>
						</div>


						<div class="row">

							<div class="col-xs-12">
								<span class="product-vars">
		                            <strong>
		                                Sex :
		                            </strong>
                            	
                            		<span sex="<?php echo $user->getSex()?>"><?php echo $user->getStringSex()?></span>
                            		
                            		<div style="display: none;" class="input_sex">
                            			<div  class="radio">
									  		<label><input value="1" type="radio" name="sex">Female</label>
										</div>
										
										<div class="radio">
									  		<label><input value="0" type="radio" name="sex">Male</label>
										</div>
										
                            		</div>
	                            	
                        		</span>
							</div>
						</div>
						
						<div class="row">

							<div class="col-xs-12">
								<span class="product-vars">
                            		<strong>
                                		Birthday :
                            		</strong>
                            			<input style="display: none" type="text" value="" name="birthday" class="form-control" placeholder="yyyy-mm-dd">
                            			<span><?php echo $user->getBirthday()?></span>
                        		</span>
							</div>

							 
						</div>


						<div class="row">

							<div class="col-xs-12">
								<span class="product-vars">
                            		<strong>
                            		    Address :
                           		 </strong>
                           		 	<input style="display: none" type="text" value="" name="address" class="form-control" placeholder="">
									<span><?php echo $user->getAddress()?></span>

                        		</span>
							</div>
						</div>

						<div class="row">

							<div class="col-xs-12">
								<span class="product-vars">
                            		<strong>
                            		    Level :
                           		 	</strong>
                           		 	<span><?php echo $user->getGroup()->getStringLevel() ?></span>
                        		</span>
							</div>
						</div>
                      </div>

                    </div>
                </div>
                </div>
			</div>
</section>

<?php if( $is_friend == true ):?>
<div class="row">
                	<div class="col-sm-12">
                		<div class="form-group">
                			<a href="<?php echo __FOLDER . 'user/action/friendList/' . $user->getUsername()?>">
                				<input type="button" class="theme_button" name="apply_coupon" value="Friend list (<?php echo $user->getTotalFriendList()?>)">
                			</a>
	                    	
	                    	<a>
	                    		<input type="button" class="theme_button" name="update_cart" value="Favorite (<?php echo $user->getTotalFavorite()?>)">	
	                    	</a>
                    		
	               		</div>
                	</div>
</div>
<?php endif;?>

<div class="widget widget_popular_entries">
	<h3 class="widget-title">Favorite list</h3>
	<div class="row list-friend">
	<?php /* @var $userFavorite User */?>
		<?php foreach ( $listUserFavorites as $userFavorite ):?>
			<div class="col-xs-6">
				<div class="list-friend">
					<div class="media">
						<p class="pull-left">
							<a idfriend="<?php echo $userFavorite->getId()?>" href="<?php echo __FOLDER . 'user/action/profile/' . $userFavorite->getUsername()?>"><?php echo htmlspecialchars( $userFavorite->getFullname() )?></a>
						</p>
						<a class="pull-left" href="#"> <img class="media-object"
							src="<?php echo $userFavorite->getLinkAvatar()?>" alt="">
						</a>
						<p class="pull-left">
							<?php if ( $userFavorite->getId() != $userSession->getId() ):?>
							<a idfriend="<?php echo $userFavorite->getId()?>" class="<?php 
								switch ( $userFavorite->getStatusForUserSession() ) {
									/**
									 * 0 : add friend
									 * 1 : unfriend
									 * 2 : unRequest
									 * @var int  */
									case 0:
										echo 'add-friend';
									break;
									case 1:
										echo 'un-friend';
									break;
									case 2:
										echo 'un-request';
									break;
								}
								?>" href="#">
								<?php
								switch ( $userFavorite->getStatusForUserSession() ) {
									case 0:
										echo 'Add Friend';
										break;
									case 1:
										echo 'unFriend';
										break;
									case 2:
										echo 'unRequest';
										break;
									}
								?>
							</a>
							<?php endif;?>
						</p>
						
						<p class="pull-left">
							<?php if ( $userFavorite->getId() != $userSession->getId() ):?>
							<a class="<?php 
								switch ( $userFavorite->getStatusFavorite() ) {
									/**
									 * 0 : add favorite => is not favorite
									 * 1 : unfavorite   => is favorite
									 * @var int  */
									case 0:
										echo 'add-favorite';
									break;
									case 1:
										echo 'un-favorite';
									break;
								}
							?>" href="#">
								<?php
								switch ( $userFavorite->getStatusFavorite() ) {
									case 0:
										echo 'Add favorite';
										break;
									case 1:
										echo 'unFavorite';
										break;
									}
								?>
							</a>
							<?php endif;?>
						</p>
					</div>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>

<script type="text/javascript" src="<?php echo __FOLDER . 'public\js\UserModule\actionController\favoriteList.js'?>"></script>
