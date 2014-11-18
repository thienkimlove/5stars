 <!-- CONTENT BOXES -->
                <div class="content-box">
                    <div class="box-header clear">
                        <ul class="tabs clear">
                            <li><a href="#data-table">Data Table</a></li>
                            <li><a href="#table">Table</a></li>
                            <li><a href="#forms">Custom Forms</a></li>
                        </ul>

                        <h2>Content Box</h2>
                    </div>

                    <div class="box-body clear">
                        <!-- TABLE -->
                        <div id="data-table">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in porta lectus. Maecenas dignissim enim quis ipsum mattis aliquet. Maecenas id velit et elit gravida bibendum. Duis nec rutrum lorem.</p> 

                            <form method="post" action="#">

                                <table class="datatable">
                                    <thead>
                                        <tr>
                                            <th class="bSortable"><input type="checkbox" class="checkbox select-all" /></th>
                                            <th>Column 1</th>
                                            <th>Column 2</th>
                                            <th>Column 3</th>
                                            <th>Column 4</th>
                                            <th>Column 5</th>
                                            <th>Column 6</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="checkbox" /></td>
                                            <td>Lorem ipsum dolor</td>
                                            <td><a href="#">John</a></td>
                                            <td>5/6/2010</td>
                                            <td><input type="text" name="input1" id="input1" value="235" class="text" size="10" /></td>
                                            <td>sed in porta lectus</td>
                                            <td>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
                                            </td>
                                        </tr>                                                        

                                    </tbody>
                                </table>

                                <div class="tab-footer clear fl">
                                    <div class="fl">
                                        <select name="dropdown" class="fl-space">
                                            <option value="option1">choose action...</option>
                                            <option value="option2">Edit</option>
                                            <option value="option3">Delete</option>
                                        </select>
                                        <input type="submit" value="Apply" id="submit1" class="submit fl-space" />
                                    </div>
                                </div>
                            </form>
                        </div><!-- /#table -->

                        <!-- TABLE -->
                        <div id="table">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in porta lectus. Maecenas dignissim enim quis ipsum mattis aliquet. Maecenas id velit et elit gravida bibendum. Duis nec rutrum lorem.</p> 

                            <form method="post" action="#">
                                <table>
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" class="checkbox select-all" /></th>
                                            <th>Column 1</th>
                                            <th>Column 2</th>
                                            <th>Column 3</th>
                                            <th>Column 4</th>
                                            <th>Column 5</th>
                                            <th>Column 6</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td><input type="checkbox" class="checkbox" /></td>
                                            <td>Lorem ipsum dolor</td>
                                            <td><a href="#">John</a></td>
                                            <td>5/6/2010</td>
                                            <td><input type="text" name="input21" id="input21" value="235" class="text" size="10" /></td>
                                            <td>sed in porta lectus</td>
                                            <td>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" class="icon16 fl-space2" alt="" title="edit" /></a>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_delete_16.png') ?>" class="icon16 fl-space2" alt="" title="delete" /></a>
                                                <a href="#"><img src="<?php echo $this->Html->imageUrl('ico_settings_16.png') ?>" class="icon16 fl-space2" alt="" title="settings" /></a>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>

                                <div class="tab-footer clear">
                                    <div class="fl">
                                        <select name="dropdown" class="fl-space">
                                            <option value="option1">choose action...</option>
                                            <option value="option2">Edit</option>
                                            <option value="option3">Delete</option>
                                        </select>
                                        <input type="submit" value="Apply" id="submit2" class="submit fl-space" />
                                    </div>
                                    <div class="pager fr">
                                        <span class="nav">
                                            <a href="#" class="first" title="first page"><span>First</span></a>
                                            <a href="#" class="previous" title="previous page"><span>Previous</span></a>
                                        </span>
                                        <span class="pages">
                                            <a href="#" title="page 1"><span>1</span></a>
                                            <a href="#" title="page 2" class="active"><span>2</span></a>
                                            <a href="#" title="page 3"><span>3</span></a>
                                            <a href="#" title="page 4"><span>4</span></a>
                                        </span>
                                        <span class="nav">
                                            <a href="#" class="next" title="next page"><span>Next</span></a>
                                            <a href="#" class="last" title="last page"><span>Last</span></a>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /#table -->

                        <!-- Custom Forms -->
                        <div id="forms">
                            <form action="#" method="post" class="form">
                                <div class="form-field clear">
                                    <label for="textfield" class="form-label fl-space2">Text field: <span class="required">*</span></label>
                                    <input type="text" id="textfield" class="text fl" name="text" />
                                </div><!-- /.form-field -->

                                <div class="form-field clear">
                                    <label for="password" class="form-label fl-space2">Password:</label>
                                    <input type="password" id="password" class="text fl" name="text" />
                                </div><!-- /.form-field -->                            

                                <div class="form-field clear">
                                    <label for="file" class="form-label fl-space2">File upload:</label>
                                    <input type="file" id="file" class="form-file fl" name="text" />
                                </div><!-- /.form-field -->                            

                                <div class="form-field clear">
                                    <label for="textarea" class="form-label fl-space2">Textarea:</label>
                                    <textarea id="textarea" class="form-textarea" cols="100" rows="6" name="text" ></textarea>
                                </div><!-- /.form-field -->                            

                                <div class="form-field clear">
                                    <label for="select" class="form-label fl-space2">Select:</label>
                                    <select id="select" class="fl">
                                        <option value="1">Value 1</option>
                                        <optgroup label="Group 1">
                                            <option value="2">Value 2</option>
                                            <option value="3">Value 3</option>
                                            <option value="4">Value 4</option>
                                        </optgroup>

                                        <optgroup label="Group 2">
                                            <option value="5">Value 5</option>
                                            <option value="6">Value 6</option>
                                            <option value="7">Value 7</option>
                                        </optgroup>                                    
                                        <option value="8">Value 8</option>
                                        <option value="9">Value 9</option>
                                    </select>
                                </div><!-- /.form-field -->        

                                <div class="clear">
                                    <div class="half fl">
                                        <div class="form-field clear">
                                            <h4>Checkbox Vertical:</h4>
                                            <div class="form-checkbox-item clear">
                                                <input type="checkbox" id="checkbox1" name="checkbox" class="checkbox fl-space" /> <label for="checkbox1" class="fl">Value 1</label>
                                            </div>

                                            <div class="form-checkbox-item clear">                                
                                                <input type="checkbox" id="checkbox2" name="checkbox" class="checkbox fl-space" /> <label for="checkbox2" class="fl">Value 2</label>
                                            </div>                                

                                            <div class="form-checkbox-item clear">                                
                                                <input type="checkbox" id="checkbox3" name="checkbox" class="checkbox fl-space" /> <label for="checkbox3" class="fl">Value 3</label>
                                            </div>                                                                    
                                        </div><!-- /.form-field-->
                                    </div>

                                    <div class="half fr">
                                        <div class="form-field clear">
                                            <h4>Checkbox Horizontal:</h4>
                                            <div class="form-checkbox-item clear fl-space2">
                                                <input type="checkbox" id="checkbox4" name="checkbox2" class="checkbox fl-space" /> <label for="checkbox4" class="fl">Value 1</label>
                                            </div>

                                            <div class="form-checkbox-item clear fl-space2">                                
                                                <input type="checkbox" id="checkbox5" name="checkbox2" class="checkbox fl-space" /> <label for="checkbox5" class="fl">Value 2</label>
                                            </div>                                

                                            <div class="form-checkbox-item clear fl-space2">                                
                                                <input type="checkbox" id="checkbox6" name="checkbox2" class="checkbox fl-space" /> <label for="checkbox6" class="fl">Value 3</label>
                                            </div>                                                                    
                                        </div><!-- /.form-field-->
                                    </div>
                                </div>

                                <div class="clear">
                                    <div class="half fl">
                                        <div class="form-field clear">
                                            <h4>Radiobuttons Horizontal:</h4>
                                            <div class="form-radio-item clear">
                                                <input type="radio" name="radio" id="radio1" class="radio fl-space" /> <label for="radio1" class="fl">Value 1</label>
                                            </div>

                                            <div class="form-radio-item clear">
                                                <input type="radio" name="radio" id="radio2" class="radio fl-space" /> <label for="radio2" class="fl">Value 2</label>
                                            </div>                                

                                            <div class="form-radio-item clear">
                                                <input type="radio" name="radio" id="radio3" class="radio fl-space" /> <label for="radio3" class="fl">Value 3</label>
                                            </div>                                                                    
                                        </div><!-- /.form-field-->
                                    </div>

                                    <div class="half fr">
                                        <div class="form-field clear">
                                            <h4>Radiobuttons Vertical:</h4>
                                            <div class="form-radio-item clear fl-space2">
                                                <input type="radio" name="radio2" id="radio4" class="radio fl-space" /> <label for="radio4" class="fl">Value 1</label>
                                            </div>

                                            <div class="form-radio-item clear fl-space2">
                                                <input type="radio" name="radio2" id="radio5" class="radio fl-space" /> <label for="radio5" class="fl">Value 2</label>
                                            </div>                                

                                            <div class="form-radio-item clear fl-space2">
                                                <input type="radio" name="radio2" id="radio6" class="radio fl-space" /> <label for="radio6" class="fl">Value 3</label>
                                            </div>                                                                    
                                        </div><!-- /.form-field-->
                                    </div>
                                </div>

                                <div class="form-field clear">
                                    <input ng-model="form.submit" type="submit" class="submit fr" value="Submit" />
                                </div><!-- /.form-field -->                                                                                                
                            </form>
                        </div><!-- /#forms -->
                    </div> <!-- end of box-body -->
                </div> <!-- end of content-box -->

                <div class="content-box">
                    <div class="box-header clear">
                        <h2>Content Box with Sidebar </h2>
                    </div>
                    <div class="box-body clear">
                        <div class="side-bar clear">
                            <div class="sidebar-column clear">
                                <div class="side-menu">
                                    <ul class="list">
                                        <li><a href="#">Lorem Ipsum Dolor</a></li>
                                        <li class="active"><a href="#">Consectetur Adipisicing</a></li>
                                        <li><a href="#">Tempor Incididunt</a></li>
                                        <li><a href="#">Dolore Magna</a></li>
                                    </ul>
                                </div>
                                <p><small>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed in porta lectus. Maecenas dignissim enim quis ipsum mattis aliquet.</small></p>
                                <p><small>Sed in porta lectus. Maecenas dignissim enim quis ipsum mattis aliquet.</small></p>
                            </div>
                            <div class="main-column clear">
                                <div class="notification note-attention">
                                    <a href="#" class="close" title="Close notification"><span>close</span></a>
                                    <span class="icon"></span>
                                    <p><strong>Attention notification:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                </div>

                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>Thumbs</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail1.jpg') ?>" alt="" class="thumb size48" /></a></td>
                                            <td><h4>Lorem ipsum dolor</h4><p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p></td>
                                            <td class="center vcenter"><img src="<?php echo $this->Html->imageUrl('ico_active_16.png') ?>" class="icon16" title="active" alt="" /></td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail2.jpg') ?>" alt="" class="thumb size48" /></a></td>
                                            <td><h4>Sed in porta lectus</h4><p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p></td>
                                            <td class="center vcenter"><img src="<?php echo $this->Html->imageUrl('ico_inactive_16.png') ?>" class="icon16" title="inactive" alt="" /></td>
                                        </tr>
                                        <tr>
                                            <td><a href="#" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail3.jpg') ?>" alt="" class="thumb size48" /></a></td>
                                            <td><h4>Quis nostrud</h4><p class="description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud.</p></td>
                                            <td class="center vcenter"><img src="<?php echo $this->Html->imageUrl('ico_active_16.png') ?>" class="icon16" title="active" alt="" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p>
                            </div>
                        </div>
                    </div> <!-- end of box-body -->
                </div> <!-- end of content-box -->

                <div class="content-box">
                    <div class="box-header clear">
                        <h2>Content Box - Detail</h2>
                    </div>
                    <div class="box-body clear">
                        <form method="post" action="#">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th class="full">Value</th>
                                        <th>Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="title">Name</td>
                                        <td class="edit-field edit-textfield long">Lorem ipsum dolor</td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Pictures</td>
                                        <td><a href="<?php echo $this->Html->imageUrl('tmp/thumbnail1.jpg') ?>" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail1.jpg') ?>" alt="" class="thumb size64 fl-space" /></a>
                                            <a href="<?php echo $this->Html->imageUrl('tmp/thumbnail2.jpg') ?>" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail2.jpg') ?>" alt="" class="thumb size64 fl-space" /></a>
                                            <a href="<?php echo $this->Html->imageUrl('tmp/thumbnail3.jpg') ?>" title="preview"><img src="<?php echo $this->Html->imageUrl('tmp/thumbnail3.jpg') ?>" alt="" class="thumb size64 fl-space" /></a></td>

                                        <td>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="title">Short Description</td>
                                        <td class="edit-field edit-textfield long"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Long Description</td>
                                        <td class="edit-field edit-textarea"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</p></td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Status</td>
                                        <td class="edit-field edit-select"><img src="<?php echo $this->Html->imageUrl('ico_inactive_16.png') ?>" class="icon16 fl" title="inactive" alt="" /></td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Available From</td>
                                        <td class="edit-field edit-date">5/6/2010</td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Available To</td>
                                        <td class="edit-field edit-date">30/6/2010</td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                    <tr>
                                        <td class="title">Priority</td>
                                        <td  class="edit-field edit-textfield">2</td>
                                        <td><a href="#" class="quick_edit"><img src="<?php echo $this->Html->imageUrl('ico_edit_16.png') ?>" alt="" class="icon16 fl" title="quick edit" /></a></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="tab-footer clear">
                                <div class="fr">
                                    <input type="submit" value="Apply Changes" id="apply" class="submit" />
                                </div>
                            </div>
                        </form>

                    </div> <!-- end of box-body -->
                </div> <!-- end of content-box -->

                <div class="content-box">
                    <div class="box-header clear">
                        <ul class="tabs clear">
                            <li><a href="#chart-bar">Pie</a></li>
                            <li><a href="#chart-pie">Bar</a></li>
                            <li><a href="#chart-line">Line</a></li>
                            <li><a href="#chart-area">Area</a></li>
                        </ul>

                        <h2>Charts</h2>
                    </div><!-- box-body -->

                    <div class="box-body clear">
                        <div id="chart-bar" class="chart">
                            <div class="chart-wrap">
                                <table class="visualize1">
                                    <caption>Pie Chart Title</caption>
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <th scope="col">food</th>
                                            <th scope="col">auto</th>
                                            <th scope="col">household</th>
                                            <th scope="col">furniture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Mary</th>
                                            <td>120</td>
                                            <td>140</td>
                                            <td>40</td>
                                            <td>100</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tom</th>
                                            <td>3</td>
                                            <td>40</td>
                                            <td>30</td>
                                            <td>45</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Laura</th>
                                            <td>80</td>
                                            <td>40</td>
                                            <td>80</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>                
                            </div><!-- /.chart-wrap -->
                        </div>
                        <div id="chart-pie">
                            <div class="chart-wrap">
                                <table class="visualize2">
                                    <caption>Bar Chart Title</caption>
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <th scope="col">food</th>
                                            <th scope="col">auto</th>
                                            <th scope="col">household</th>
                                            <th scope="col">furniture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Mary</th>
                                            <td>120</td>
                                            <td>140</td>
                                            <td>40</td>
                                            <td>100</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tom</th>
                                            <td>3</td>
                                            <td>40</td>
                                            <td>30</td>
                                            <td>45</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Laura</th>
                                            <td>80</td>
                                            <td>40</td>
                                            <td>80</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>                
                            </div><!-- /.chart-wrap -->
                        </div>
                        <div id="chart-line">
                            <div class="chart-wrap">
                                <table class="visualize3">
                                    <caption>Line Chart Title</caption>
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <th scope="col">food</th>
                                            <th scope="col">auto</th>
                                            <th scope="col">household</th>
                                            <th scope="col">furniture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Mary</th>
                                            <td>120</td>
                                            <td>140</td>
                                            <td>40</td>
                                            <td>100</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tom</th>
                                            <td>3</td>
                                            <td>40</td>
                                            <td>30</td>
                                            <td>45</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Laura</th>
                                            <td>80</td>
                                            <td>40</td>
                                            <td>80</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- /.chart-wrap -->
                        </div>
                        <div id="chart-area">
                            <div class="chart-wrap">
                                <table class="visualize4">
                                    <caption>Area Chart Title</caption>
                                    <thead>
                                        <tr>
                                            <td></td>
                                            <th scope="col">food</th>
                                            <th scope="col">auto</th>
                                            <th scope="col">household</th>
                                            <th scope="col">furniture</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row">Mary</th>
                                            <td>120</td>
                                            <td>140</td>
                                            <td>40</td>
                                            <td>100</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Tom</th>
                                            <td>3</td>
                                            <td>40</td>
                                            <td>30</td>
                                            <td>45</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Laura</th>
                                            <td>80</td>
                                            <td>40</td>
                                            <td>80</td>
                                            <td>1</td>
                                        </tr>
                                    </tbody>
                                </table>                
                            </div><!-- /.chart-wrap -->
                        </div>                                                            
                    </div><!-- /.box-body -->
                </div><!-- /.content-box -->

                <div class="clear">
                    <div class="content-box half fl">
                        <div class="box-header">
                            <h2>Half Box Left</h2>
                        </div>
                        <div class="box-body">
                            <p>Nam posuere, felis sed feugiat viverra, quam felis dapibus eros, vitae pulvinar nisl quam ut eros. Curabitur eget fringilla mi. Vivamus sed justo sit amet elit malesuada bibendum. Pellentesque consectetur blandit nisl, a eleifend arcu adipiscing eu. In et neque nec urna mollis fermentum gravida at turpis. Vestibulum rhoncus commodo porttitor. Maecenas mauris libero, suscipit posuere adipiscing dignissim, varius non felis. Nulla vel lorem at enim rhoncus porttitor.</p> 
                            <p>Etiam sem enim, scelerisque eu laoreet ac, eleifend ut augue. Ut consectetur eros urna. Nulla nulla justo, venenatis eu sodales eget, pulvinar et nunc. Donec nisl massa, ultrices at aliquet nec, lobortis eu magna. Pellentesque metus eros, adipiscing a dapibus vitae, bibendum id arcu. Cras dignissim, libero tincidunt aliquam venenatis, lectus quam auctor lorem, at semper magna quam et sapien. Pellentesque volutpat mi nulla. Quisque nec magna magna. Etiam justo nulla, imperdiet sit amet commodo at, pretium quis augue. Mauris euismod congue lorem id euismod. Vivamus lorem eros, dapibus quis ullamcorper consequat, posuere non ante.</p>
                        </div> <!-- end of box-body -->
                    </div> <!-- end of content-box -->

                    <div class="content-box half fr">
                        <div class="box-header">
                            <h2>Half Box Right</h2>
                        </div>
                        <div class="box-body">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec tristique, lorem id hendrerit sodales, nisl felis sollicitudin lacus, et facilisis felis quam at quam. Nullam vel nunc at sapien sagittis feugiat. Vestibulum est eros, condimentum ac sodales vel, iaculis vitae neque.</p> 
                            <p>Nam nisl odio, scelerisque non venenatis quis, venenatis a leo. Cras non vehicula justo. Nam vel arcu sem. Suspendisse quam enim, dictum quis lacinia sed, lobortis eget libero. Suspendisse potenti. Suspendisse et ante vitae turpis vestibulum fermentum nec nec elit. Suspendisse ullamcorper lacus in arcu mollis fringilla porta mi placerat. Ut at elit non diam tristique scelerisque. Phasellus in condimentum nisi. Sed sit amet ligula sem, ac venenatis nisi. In a condimentum justo. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Pellentesque aliquet auctor velit vitae lacinia. Cras velit libero, cursus ac pulvinar quis, egestas vitae dolor. Integer imperdiet nulla ut tortor bibendum pulvinar. Fusce pellentesque fermentum gravida.</p>
                        </div> <!-- end of box-body -->
                    </div> <!-- end of content-box -->
                </div><!-- /.clear -->


                <!-- end of content-box -->