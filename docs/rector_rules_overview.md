# 1 Rules Overview

## AppMakeToAppHelperRector

Replace `app()->make(...)` with `app(...)`

- class: [`AlanScott75\RectorLaravel\Rector\MethodCall\AppMakeToAppHelperRector`](../src/Rector/MethodCall/AppMakeToAppHelperRector.php)

```diff
 use \Illuminate\Contracts\Foundation\Application;

 class MyController
 {
     public function store()
     {
-        return app()->make('class');
+        return app('class');
     }
 }
```

<br>
