<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <settings.input_field>
                        <label>Input field</label>
                        <config>
                            <type>input</type>
                            <eval>trim</eval>
                        </config>
                    </settings.input_field>
                    <settings.int_field>
                        <label>Int field</label>
                        <config>
                            <type>input</type>
                            <eval>int</eval>
                        </config>
                    </settings.int_field>
                    <settings.nullable_int_field>
                        <label>Nullable int field</label>
                        <config>
                            <type>input</type>
                            <eval>int,null</eval>
                        </config>
                    </settings.nullable_int_field>
                    <settings.double2_field>
                        <label>double2 field</label>
                        <config>
                            <type>input</type>
                            <eval>double2</eval>
                        </config>
                    </settings.double2_field>
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
                    <settings.input_field>
                        <label>Input field</label>
                        <config>
                            <type>input</type>
                            <eval>trim</eval>
                        </config>
                    </settings.input_field>
                    <settings.int_field>
                        <label>Int field</label>
                        <config>
                            <type>number</type>
                        </config>
                    </settings.int_field>
                    <settings.nullable_int_field>
                        <label>Nullable int field</label>
                        <config>
                            <type>number</type>
                            <eval>null</eval>
                        </config>
                    </settings.nullable_int_field>
                    <settings.double2_field>
                        <label>double2 field</label>
                        <config>
                            <type>number</type>
                        <format>decimal</format></config>
                    </settings.double2_field>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
