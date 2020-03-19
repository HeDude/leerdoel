<?php
class Menu_Dropdown_Learning_goals
{
    private $gap = '    ';

    private $learning_goals = array
    (
        array
        (
            'label' => 'menu1',
            'submenus' => array
            (
                array
                (
                    'label' => 'submenu11',
                    'submenus' => array
                    (
                        array
                        (
                            'label' => 'submenu111'
                        ),
                        array
                        (
                            'label' => 'submenu112'
                        )
                    )
                ),
                array
                (
                    'label' => 'submenu12'
                )
            )
        ),
        array
        (
            'label' => 'menu2',
            'submenus' => array
            (
                array
                (
                    'label' => 'submenu21'
                )
            )
        )
    );

    public function __construct()
    {
        $learning_goals_original = json_decode( file_get_contents( 'goal_nl.json' ), true );
        $learning_goals = array();
        foreach ( $learning_goals_original as $id => $learning_goal_original )
        {
            $item = &$learning_goals_original[ $id ];
            if ( empty( $learning_goal_original[ 'parent' ] ) )
            {
                $learning_goals[ $id ] = &$item;
            }
            elseif ( isset( $learning_goals_original[ $item[ 'parent' ] ] ) )
            {
                $learning_goals_original[ $item[ 'parent' ] ][ 'children' ][ $id ] = &$item;
            }
            else
            {
                $learning_goals[ '_orphans_' ][ $id ] = &$item;
            }
        }
        $this->learning_goals = $learning_goals;
    }

    public function get( string $indent = '    ' ) : string
    {
        $item = reset ( $this->learning_goals );
        $menu  = $indent . '<ul class="goal_list margin-top-lg margin-bottom-lg">' . PHP_EOL;
        $menu .= $indent . $this->gap . '<li class="goal_list_item goal_list_item--has-children">' . PHP_EOL;
        $menu .= $indent . $this->gap . $this->gap . '<input class="goal_list_input" type="checkbox" name ="' . $item[ 'title' ] . '" id="' . $item[ 'title' ] . '">' . PHP_EOL;
        $menu .= $indent . $this->gap . '<label class="goal_list_label" for="' . $item[ 'title' ] . '"><a class="goal_edit" href="https://www.leerdoeleditor.nl?name=' .  urlencode( $item[ 'name' ] ) . '&description=' . urlencode( $item[ 'description' ] ) . '" target="_blank">&nbsp;&#x2710;&nbsp;</a>' . $item[ 'name' ] . '&nbsp;<span>' . $item[ 'description' ] . '</span></label>' . PHP_EOL;
        $menu .= PHP_EOL;
        $menu .= $this->get_Submenu( $item['children'], $indent . $this->gap . $this->gap );
        $menu .= $indent . $this->gap . '</li>' . PHP_EOL;
        $menu .= $indent . '</ul>' . PHP_EOL;
        return $menu;
    }

    private function get_Submenu( array $submenu = array(), string $indent = '', int $level = 1 ) : string
    {
        if ( empty( $submenu ) )
        {
            return '';
        }
        $view = '';
        $view .= $indent . '<ul class="goal_list_sub goal_list_sub--l' . $level . '">' . PHP_EOL;
        foreach ( $submenu as $key => $item )
        {
            if ( !array_key_exists( 'title', $item ) )
            {
                break;
            }
            if ( array_key_exists( 'children', $item ) )
            {
                $view .= $indent . $this->gap . '<li class="goal_list_item goal_list_item--has-children">' . PHP_EOL;
                $view .= $indent . $this->gap . $this->gap . '<input class="goal_list_input" type="checkbox" name ="' . $item[ 'title' ] . '" id="' . $item[ 'title' ] . '">' . PHP_EOL;
                $view .= $indent . $this->gap . $this->gap . '<label class="goal_list_label" for="' . $item[ 'title' ] . '"><a class="goal_edit" href="https://www.leerdoeleditor.nl?name=' .  urlencode( $item[ 'name' ] ) . '&description=' . urlencode( $item[ 'description' ] ) . '" target="_blank">&nbsp;&#x2710;&nbsp;</a>' . $item[ 'name' ] . '&nbsp;<span>' . $item[ 'description' ] . '</span></label>' . PHP_EOL;
                $view .= PHP_EOL;
                $view .=  $this->get_Submenu( $item['children'],  $indent . $this->gap . $this->gap, $level + 1 );
                $view .=  $indent . $this->gap . '</li>' . PHP_EOL;
            }
            else
            {
                $view .=  $indent . $this->gap . '<li class="goal_list_item goal_list_label goal_list_label--icon-img"><a class="goal_edit" href="https://www.leerdoeleditor.nl?name=' .  urlencode( $item[ 'name' ] ) . '&description=' . urlencode( $item[ 'description' ] ) . '" target="_blank">&nbsp;&#x2710;&nbsp;</a>' . $item[ 'name' ] . '&nbsp;<span>' . $item[ 'description' ] . '</span></li>' . PHP_EOL;
            }
        }
        $view .= $indent . '</ul>' . PHP_EOL;
        return $view;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <title>Leerdoelen</title>
</head>
<body style="padding: 3em 0">
    <section class="container max-width-lg">
        <div class="text-component text-center">
            <h1>Leerdoelen</h1>
        </div>
<?php $menu = new Menu_Dropdown_Learning_goals; echo $menu->get('        '); ?>
    </section>
</body>
</html>
