<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <settings.colorPicker>
                        <label>Label</label>
                        <config>
                            <type>input</type>
                            <renderType>colorpicker</renderType>
                            <required>1</required>
                            <size>20</size>
                            <max>1234</max>
                            <eval>trim,null</eval>
                            <valuePicker>
                                <items type="array">
                                    <numIndex index="0" type="array">
                                        <numIndex index="0">typo3 orange</numIndex>
                                        <numIndex index="1">#FF8700</numIndex>
                                    </numIndex>
                                </items>
                            </valuePicker>
                        </config>
                    </settings.colorPicker>
                    <settings.colorPicker2>
                        <label>Label</label>
                        <config>
                            <type>input</type>
                            <renderType>colorpicker</renderType>
                            <eval>trim</eval>
                        </config>
                    </settings.colorPicker2>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
-----
<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <settings.colorPicker>
                        <label>Label</label>
                        <config>
                            <type>color</type>
                            <required>1</required>
                            <size>20</size>
                            <eval>null</eval>
                            <valuePicker>
                                <items type="array">
                                    <numIndex index="0" type="array">
                                        <numIndex index="0">typo3 orange</numIndex>
                                        <numIndex index="1">#FF8700</numIndex>
                                    </numIndex>
                                </items>
                            </valuePicker>
                        </config>
                    </settings.colorPicker>
                    <settings.colorPicker2>
                        <label>Label</label>
                        <config>
                            <type>color</type>
                        </config>
                    </settings.colorPicker2>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
