<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<?php $netProps = $device->NetworkProperty; ?>

<form action="/device/delete/network-deps" method="post">
    <input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

    <div class="table-row-net-dep">
        <?php if (isset($depIntfProps) && count($depIntfProps) > 0) : ?>
            <h3>Defined Network Dependencies</h3>
            <table id="defined-interface-deps"  class="defined-deps">
                <thead>
                    <tr>
                        <th style="width: 35%;">Local Interface</th>
                        <th style="width: 55%;">Remote Interface Dependencies</th>
                        <th style="width: 10%;">Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        foreach ($depIntfProps as $intfProp) {

                            $firstRow = TRUE; // changing service starts new rowspan

                            echo '<tr>';

                            $rowSpan = count($intfProp['deps']);
                            echo "<td class=\"local\" rowspan=\"{$rowSpan}\">";

                            echo $intfProp['nic'];
                            echo ' (Blade: ' . $intfProp['blade'] . ' Port: ' . $intfProp['port'] . '  VLAN: ' . $intfProp['vlan'] .')';

                            echo '</td><!-- END td.local -->';

                            foreach ($intfProp['deps'] as $dep) {

                                if (!$firstRow) {
                                    echo '<tr>';
                                }

                                echo '<td class="remote">';
                                echo $dep['deviceName'] . ' => ';
                                echo $dep['nic'];
                                echo ' (Blade: ' . $dep['blade'] . ' Port: ' . $dep['port'] . '  VLAN: ' . $dep['vlan'] .')';

                                echo '</td><!-- END td.local -->';

                                echo "<td class=\"delete\"><input type=\"checkbox\" name=\"del-net-deps[]\" value=\"{$intfProp['id']}_{$dep['id']}\"/></td>";

                                echo '</tr>';
                                $firstRow = FALSE; // After adding first dep, all new rows added to rowspan
                            }
                        }
                    ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div><!-- END .props-row -->
    <div class="button-row">
        <input type="submit" id="submit-delete-net-deps" name="submit-delete-net-deps"
                class="med" value="Delete Network Dependencies"
                <?php
                    $test = array('IT Operations');
                    if (isset($device->FunctionalArea)) {
                        $test[] = $device->FunctionalArea->name;
                    }
                    echo inputelems::enable($test, $userFuncAreas, FALSE);
                ?>
        />
    </div><!-- END .props-row -->
</form>

<form action="/device/update/network-deps" method="post">
    <input type="hidden" id="deviceName" name = "deviceName" value="<?php echo $device->name; ?>">

    <div class="multicontent-row-net-dep">
        <div class="multi-select-net-dep">

            <label for="list-local-interfaces" class="multi-label-net-dep">Local Interface</label><br/>
            <select id="list-local-interfaces" name="list-local-interfaces"
                    class="list-local-interfaces wide
                        <?php
                            $test = array('IT Operations');
                            if (isset($device->FunctionalArea)) {
                                $test[] = $device->FunctionalArea->name;
                            }
                            echo inputelems::enable($test, $userFuncAreas);
                        ?>" >
                    <option value="0">Please Select Interface...</option>
                    <?php
                        foreach($definedInterfaces as $interface) {
                            if ($interface->id) {
                                echo "<option value=\"{$interface->id}\"";
                                echo " >";
                                echo $interface->nic . ' ' .
                                    ' (' . $interface->mac . ')';
                                echo "</option>";
                            }
                        }
                    ?>
                </select>
                <br /><br />
                <label for="intf-dep-device-name" class="multi-label-net-dep">Select Remote Device</label><br/>
                <div id="autocomplete-device-intf-dep" class="autocomplete-device-intf-dep">
                        <input type="text" id="intf-dep-device-name" name="name" class="intf-dep-device-name autocompleter-text wide"/>
                        <div id="autocompleter-device-intf-list-wrapper" class="autocompleter-device-intf-list-wrapper">
                            <div id="device-interface-suggestions" class="device-interface-suggestions autocompleter-list" style="display: none;"></div>
                        </div>
                </div>
                <div id="indicator2" class="indicator2" style="display: none">
                        <img src="/gfx/page_wait_28.gif" alt="Working..." />
                </div>
            </div><!-- END .multi-select-net-dep -->

            <div class="multi-select-net-dep">
                <label for="list-remote-def-interfaces" class="multi-label-net-dep">Defined Remote Interfaces</label><br/>
                <select multiple="multiple" id="list-remote-def-interfaces" name="list-remote-def-interfaces" size="15"
                        class="list-remote-def-interfaces wide
                            <?php
                                $test = array('IT Operations');
                                if (isset($device->FunctionalArea)) {
                                    $test[] = $device->FunctionalArea->name;
                                }
                                echo inputelems::enable($test, $userFuncAreas);
                            ?>" >
                </select>
        </div><!-- END .multi-select-net-dep -->

        <div class="mid-col-net-dep">
            <div id="add-remote-interface" class="button">
                &nbsp;&gt;&nbsp;
            </div>
        </div>

        <div class="multi-select-net-dep">
            <label for="remote-interfaces-deps" class="multi-label-net-dep">Remote Interface Dependencies</label><br/>
            <select multiple="multiple" id="remote-interfaces-deps" name="remote-interfaces-deps[]" size="15"
                    class="enabled remote-interfaces-deps wide
                        <?php
                            $test = array('IT Operations');
                            if (isset($device->FunctionalArea)) {
                                $test[] = $device->FunctionalArea->name;
                            }
                            echo inputelems::enable($test, $userFuncAreas);
                        ?>" >
            </select>
        </div><!-- END .multi-select-net-dep -->
    </div><!-- END .multicontent-row-net-dep -->

    <div class="button-row">
        <input type="submit" id="submit-net-deps" name="submit-net-deps"
                class="med" value="Update Network Dependencies"
                <?php
                    $test = array('IT Operations');
                    if (isset($device->FunctionalArea)) {
                        $test[] = $device->FunctionalArea->name;
                    }
                    echo inputelems::enable($test, $userFuncAreas, FALSE);
                ?>
        />
    </div><!-- END .props-row -->
</form>