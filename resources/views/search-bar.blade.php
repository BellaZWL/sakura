<?php

namespace App\Admin\Extensions\Nav;

class Links
{
    public function __toString()
    {
        return <<<HTML
				<div>
					<ul>
						<li class="active">
							 <a href="#">query管理</a>
						</li>
						<li class="dropdown">
							 <a href="#" class="dropdown-toggle" data-toggle="dropdown">DR管理<strong class="caret"></strong></a>
							<ul class="dropdown-menu">
								<li>
									 <a href="#">新建</a>
								</li>
								<li>
									 <a href="#">修改</a>
								</li>
							</ul>
						</li>
					    <li>
							<a href="#">新建主题</a>
						</li>
						<li>
							<a href="#">logs查看</a>
						</li>
					</ul>
				</div>
HTML;
    }
}