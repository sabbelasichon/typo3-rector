<T3DataStructure>
    <sheets>
        <sDEF>
            <ROOT>
                <sheetTitle>sheetTitle</sheetTitle>
                <type>array</type>
                <el>
                    <settings.nullable_field_trimmed>
                        <label>Nullable field trimmed</label>
                        <config>
                            <eval>null,trim</eval>
                        </config>
                    </settings.nullable_field_trimmed>
                    <settings.nullable_field>
                        <label>Nullable field</label>
                        <config>
                            <eval>null</eval>
                        </config>
                    </settings.nullable_field>
                    <settings.nullable_field_partially_migrated>
                        <label>Nullable field</label>
                        <config>
                            <eval>null,trim</eval>
                            <nullable>true</nullable>
                        </config>
                    </settings.nullable_field_partially_migrated>
                    <settings.migrated_nullable_field>
                        <label>Migrated nullable field</label>
                        <config>
                            <nullable>true</nullable>
                            <eval>trim</eval>
                        </config>
                    </settings.migrated_nullable_field>
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
                    <settings.nullable_field_trimmed>
                        <label>Nullable field trimmed</label>
                        <config>
                            <eval>trim</eval>
                        <nullable>true</nullable></config>
                    </settings.nullable_field_trimmed>
                    <settings.nullable_field>
                        <label>Nullable field</label>
                        <config>
                        <nullable>true</nullable></config>
                    </settings.nullable_field>
                    <settings.nullable_field_partially_migrated>
                        <label>Nullable field</label>
                        <config>
                            <eval>trim</eval>
                        <nullable>true</nullable></config>
                    </settings.nullable_field_partially_migrated>
                    <settings.migrated_nullable_field>
                        <label>Migrated nullable field</label>
                        <config>
                            <nullable>true</nullable>
                            <eval>trim</eval>
                        </config>
                    </settings.migrated_nullable_field>
                </el>
            </ROOT>
        </sDEF>
    </sheets>
</T3DataStructure>
