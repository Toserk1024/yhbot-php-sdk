## 关于
<strong>本人懒得写Readme，以下使用文档使用AI生成，<br>如看不懂可查看`example.php`，里面有调用的实例</strong>
## 使用文档
### `send`函数

#### 函数签名：
```php
send($object, $type, $content, $batch = false, $buttons = null)
```

- **$object**：接收对象，传入一个包含id和type键值的数组。当需要批量发送时，将id改为一个ids的数组。
- **$type**：发送内容的类型，可选参数为text、image、markdown和file。
- **$content**：发送内容的主体。若类型为file，请传入包含name和url键值的数组。
- **$batch**：是否启用批量发送，传入布尔值，默认为false。
- **$buttons**：消息中的按钮数据，具体规范请参考官方文档。

该发送函数通过传入不同的参数实现向指定对象发送不同类型的内容，支持单一对象和批量对象发送。您可以根据您的需求调用该函数来完成消息发送操作。
### `edit`函数

#### 函数签名：
```php
edit($msg_id, $object, $type, $content, $buttons = null)
```

#### 参数：

- **$msg_id**：需要编辑的消息的ID，除了修改的内容，其他跟原消息保持一致

#### 功能：

`edit`函数用于编辑已发送的消息。您可以通过指定消息ID和接收者信息来确定需要编辑的消息，然后通过`type`和`content`参数来设置新的消息内容。如果您需要在消息中添加按钮，可以通过`buttons`参数来实现。

### `set_board`函数

#### 函数签名：
```php
set_board($type, $content, $is_all = true, $object = null)
```

#### 参数：

- **$type**：消息的内容类型。这是一个字符串，可选的值有`text`、`markdown`和`html`。
- **$content**：消息的内容。这是一个字符串。
- **$is_all**：是否为所有用户设置消息板。这是一个布尔值，默认为`true`。
- **$object**：接收者的信息。这是一个数组，包含`id`和`type`两个键值。

#### 功能：

`set_board`函数用于设置一个消息板。您可以通过`type`和`content`参数来设置消息板的内容，通过`is_all`参数来确定是否为所有用户设置消息板，如果为`false`，则为特定用户设置消息板。

### `unset_board`函数

#### 函数签名：
```php
unset_board($is_all = true, $object = null)
```

#### 参数：

- **$is_all**：是否为所有用户取消设置消息板。这是一个布尔值，默认为`true`。
- **$object**：接收者的信息。这是一个数组，包含`id`和`type`两个键值。

#### 功能：

`unset_board`函数用于取消设置的消息板。您可以通过`is_all`参数来确定是否为所有用户取消设置消息板，如果为`false`，则为特定用户取消设置消息板。

### `write_log`函数

#### 函数签名：
```php
write_log($action)
```

#### 参数：

- **$action**：操作的名称。

#### 功能：

`write_log`函数用于写入日志。您可以通过`action`参数来指定操作的名称，然后该函数会将操作的详细信息写入日志。