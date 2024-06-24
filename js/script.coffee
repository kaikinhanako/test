# coffeescript
$ ->
    # TODO: それぞれのフォームでのチェック

    # init variables
    td_boxs            = $('table.table-words td').not('.emp')
    ans_form           = $('#answer-form')
    process_count_span = $('span#process_count')
    btn_end            = $('#submit-end')
    btn_start          = $('#submit-start')
    btn_tweet          = $('#submit-tweet')
    btn_answer         = $('#submit-answer')
    time_box           = $('#time-box')
    word_boxs          = $('.wordbox')
    all_word_num       = td_boxs.size()
    game_id            = $('#game-id').val()
    game_name          = $('#game-name').val()
    game_mode          = $('#game-mode').val()
    word_unit          = $('#word-unit').val()
    timer_btn          = $('#timer-toggle-btn')
    timer_input        = $('#timer-input')
    input_tag_form     = $('#input_tags')
    tag_check_box      = $('#tag-check')
    timer_input_val    = 0
    timer_mode         = 0
    solve_count        = 0
    game_flag          = 0
    dtime              = 0
    start_time         = null
    timer_id           = null
    data_start_id      = []
    stc = $.SuperTextConverter()
    btn_end.hide()
    btn_answer.hide()
    btn_tweet.hide()
    input_game_name = $('#input_game_name')

    JUDGE_NG = 0
    JUDGE_OK = 1
    JUDGE_ALREADY = 2

    to_ans_kana = (str) ->
        str = str.replace(/[()（）・\s\t/]/g, '').replace(/[<]/g, '__r').replace(/([&＆]|アンド|あんど)/g, '__a').replace(/[>]/g, '__l').replace(/[-ー]/g, '__h').replace(/\./g, '__d').replace(/([+]|たす|タス|ぷらす|プラス)/g, '__p').replace(/#/g, '__s').replace(/[?？]/, '__q').replace(/[!！]/, '__e')
        return stc.toHankaku(stc.toHiragana(stc.killHankakuKatakana(str)),
            convert:
                punctuation: false
        ).toLowerCase()

    replay = () ->
        word = ans_form.val()
        if game_flag != 1 || !word
            return
        # 全角ひらがな半角英数字に統一する
        word_k = to_ans_kana(word)
        console.log word_k

        c = word_k.length
        judge = JUDGE_NG
        loop
            word_kp = word_k
            for sp_end in [0...c]
                sp_end = c - sp_end
                word_kt = word_k.substr(0, sp_end)
                td = $("td[ansc=#{word_kt}]")
                continue if td.size() < 1
                if td.hasClass "ok"
                    judge = JUDGE_ALREADY if judge != JUDGE_OK && word_k == word_kt
                    continue
                judge = JUDGE_OK
                word_k = word_k.substr(sp_end)
                ans_form.val(word_k)
                # 正解した場合
                td.each ->
                    ans = $(@).attr 'ans'
                    $(@).html(ans)
                    
                td.addClass('ok')
                # 人気アイテムの統計
                data_start_id.push td.attr 'nid'
                solve_count += td.size()
                process_count_span.html(solve_count)
                if solve_count == all_word_num
                    game_end()
                break if word_k == ""
            console.log word_k
            break if word_k == "" || word_k == word_kp

        switch judge
            when JUDGE_OK
                turn_off $('.judge-ok')
            when JUDGE_NG
                turn_off $('.judge-ng')
            when JUDGE_ALREADY
                turn_off $('.judge-already')

    game_end = ->
        game_flag = 0
        clearInterval(timer_id)
        btn_end.hide()
        btn_answer.hide()
        btn_start.show()
        btn_tweet.show()
        ans_form.attr('disabled')
        timer_btn.removeClass("disabled")
        ng_ids = []
        td_boxs.not('.ok').each ->
            $(@).html($(@).attr('ans'))
            $(@).addClass('ng')
            ng_ids.push $(@).attr 'nid'
        if all_word_num < 5 || data_start_id.length >= 1
            post_result(data_start_id, ng_ids)

    game_start = ->
        game_flag = 2
        start_time = new Date().getTime()
        btn_start.hide()
        btn_tweet.hide()
        btn_answer.show()
        btn_end.show()
        dtime = 0
        solve_count = 0
        data_start_id = []
        btn_answer.attr('disabled', '')
        btn_end.attr('disabled', '')
        process_count_span.html(0)
        time_box.css('color', 'red')
        timer_btn.addClass("disabled")
        ans_form.removeAttr('disabled')
        td_boxs.each ->
            $(@).html($(@).attr('ansf'))
            $(@).removeClass('ok')
            $(@).removeClass('ng')
        timer_id = setInterval ->
            my_disp_down()
        ,10

    game_start_open = ->
        game_flag = 1
        btn_answer.removeAttr('disabled')
        btn_end.removeAttr('disabled')
        clearInterval(timer_id)
        start_time = new Date().getTime()
        if timer_mode == 1
            timer_input_val = timer_input.val()
            start_time += 60 * 1000 * timer_input_val
#            console.log("timermode gone: " + timer_input_val)
        time_box.css('color', 'black')
        timer_id = setInterval ->
            my_disp()
        ,10

    to_double0 = (n)->
        if n < 10
            return '0' + n
        return n

    my_disp = ->
        if timer_mode == 1
            dtime = start_time - new Date().getTime()
            if dtime < 0
                game_end()
                dtime = 0
        else
            dtime = new Date().getTime() - start_time
        myH = Math.floor(dtime/(60*60*1000))
        dtime = dtime-(myH*60*60*1000)
        myM = Math.floor(dtime/(60*1000))
        dtime = dtime-(myM*60*1000)
        myS = Math.floor(dtime/1000)
        myMS = Math.floor(dtime / 10 % 100)
        time_box.html(to_double0(myH) + ":" + to_double0(myM) + ":" + to_double0(myS) + "." + to_double0(myMS))

    my_disp_down = ->
        dtime = 3000 - (new Date().getTime() - start_time)
        if dtime <= 0
            game_start_open()
            return
        myS = Math.floor(dtime/1000) +  1
        time_box.html("--:--:" + to_double0(myS) + ".--")

    btn_start.click ->
        ans_form.focus()
        game_start()

    btn_end.click ->
        game_end()

    post_result = (start_ids, ng_ids)->
        start_ids = start_ids.filter (e)->
            return !!e
        ng_ids = ng_ids.filter (e)->
            return !!e
        #console.log start_ids
        #console.log ng_ids
        data =
            start_ids: start_ids.join ","
            ng_ids: ng_ids.join ","
            time: get_times(false)
            is_typing:  game_mode == 'typing'
        $.ajax(
            type: "POST",
            url: "../game/result/" + game_id
            data: data,
            success: (res) ->
                console.log res
            error: ->
                console.log 'result post error'
        )

    ans_form.on("keypress", (e) ->
        if e.which == 13
            if game_flag == 0
                game_start()
            else
                replay()
    )
    btn_answer.click ->
        replay()
        ans_form.focus()

    add_list = ->
        add_text = $('#input_add').val()
        reg_text = '['
        reg_text += "," if $("#checkbox-split-comma").prop('checked')
        reg_text += "\n" if $("#checkbox-split-return").prop('checked')
        reg_text += " " if $("#checkbox-split-space").prop('checked')
        reg_text += "\t" if $("#checkbox-split-tab").prop('checked')
        reg_text += ']'
        
        add_words = add_text.split(new RegExp(reg_text))
        for i in [0...add_words.length]
            add_words[i] = add_words[i].replace(/[,\n\t ]/g, '')

        add_words = add_words.filter (e)->
            return !!e
        $.each(add_words, (i, v) ->
            add_words[i] = v.substr(0, 20)
        )

        add_words = $.unique(add_words)
        word_boxs.each ->
            if !$(@).val()
                $(@).val(add_words.shift())
                if add_words.length == 0
                    return false
        $('#input_add').val("")
        wordbox_change()

    $('#submit-add').click add_list
    $('#input_add').on("keypress", (e) ->
        if e.which == 13
            add_list()
            return false
    )

    # チェックボタン押下
    get_forms = ->
        wordlist = []
        word_boxs.each ->
            v = $.trim $(@).val()
            wordlist.push v if v
        words_text = wordlist.join(',')
        game_name = $.trim(input_game_name.val())
        words_unit = $.trim $('#input_words_unit').val()
        if ds = $('#input_description').val()
            game_description = $.trim ds
        else
            game_description = ""
        category = $.trim $('#input_category').val()
        game_tags = $.trim input_tag_form.val()
        if words_text == '' || !game_name? || game_name == "" || !words_unit? || words_unit == "" || !category?
            return false
        return data =
            game_name: game_name
            words_unit: words_unit
            game_description: game_description
            game_tags: game_tags
            game_category: category
            words_list_text: words_text

    # 合計numの更新
    wordbox_change = ->
        c = 0
        word_boxs.each ->
            c++ if $(@).val() != ""
        # 半角スペース3桁埋め
        $('#num').html(('   ' + c).substr(-3).replace(' ', '&nbsp;'))

    word_boxs.hover ->
        $(@).next('.delete-btn').show()
    ,->
        $(@).next('.delete-btn').hide()

    $('.delete-btn').hover ->
        $(@).show()
    ,->
        $(@).hide()

    $('.delete-btn').click ->
        $(@).prev('input').val('')
        return false


    wordbox_clear = ->
        word_boxs.each ->
            $(@).val("")


    $('#submit-clear').click wordbox_clear

    name_check = ->
        data =
            name: input_game_name.val()
        $('#check-name').html('check')
        $('#check-name').parent().parent().removeClass('has-error')
        $.ajax(
            type: "POST",
            url: "make/check",
            data: data,
            success: (res) ->
                if res == "s"
                    $('#check-name').html('使うことのできるタイトルです')
                    $('#check-name').css('color', 'green')
                else
                    $('#check-name').html('既に使われているタイトルです')
                    $('#check-name').parent().parent().addClass('has-error')
                    $('#check-name').css('color', 'red')
            error: ->
                console.log 'check error'
        )
    input_game_name.change -> name_check()

    $('#check-btn').click ->
        ok = true
        if gn = $.trim input_game_name.val()
            gn
        else
            ok = false
        $('#input_words_unit').val()
        $('#input_descripiton').val()
    # 送信ボタン押下
    $('#submit-btn').click ->
        console.log "submit-btn get!"
        if !(data = get_forms())
            console.log 'form no comp'
            return false
        console.log data
        $.ajax(
            type: "POST",
            url: "make/post",
            data: data,
            success: (res) ->
                console.log res
                ress = res.split(':')
                res_code = ress[0]
                res_text = ress[1]
                switch res_code
                    when 'e'
                        console.log "ゲーム名が既に使われています"
                    when 's'
                        location.href = 'g/' + res_text
            error: ->
                console.log 'connect error'
        )

    word_boxs.change -> wordbox_change

    btn_tweet.click ->
        hashtags = '言えるかな'
        time = get_time_str()
        if game_mode != 'typing'
            if all_word_num == solve_count
                text = "#{game_name}を#{solve_count}#{word_unit}全て言うことが出来ました！[#{time}]"
            else
                text = "#{game_name}を#{all_word_num}#{word_unit}中#{solve_count}#{word_unit}言えました[#{time}]"
            if game_mode != 'normal'
                text += "<#{game_mode}>"
        else
            text = "#{game_name}#{solve_count}#{word_unit}を[#{time}]でタイプしました"
        share_url = location.href
        url = "https://twitter.com/intent/tweet?hashtags=#{hashtags}&text=#{text}&url=#{share_url}"
        window.open(url)

    $('#btn-please').click ->
        hashtags = '言えるかな作って'
        text = ""
        url = "https://twitter.com/intent/tweet?hashtags=#{hashtags}&text=#{text}"
        window.open(url)
    
    get_time_str = () ->
        times = get_times(true)
        return (if times["h"] then times["h"] + '時間' else '') + "" + (if times["m"] then times["m"] + '分' else '') + "" + times["s"] + '秒'

    get_times = (is_split)->
        time = time_box.html()
        times = []
        ts = time.split(':')
        ts2 = ts[2].split('.')
        if timer_mode == 1
            dtime = (((timer_input_val - (ts[0] * 60 + ts[1])) * 60 - ts2[0]) * 1000) - (ts2[1] * 10)
            return dtime if !is_split
            times["h"] = Math.floor(dtime/(60*60*1000))
            dtime = dtime-(times["h"]*60*60*1000)
            times["m"] = Math.floor(dtime/(60*1000))
            dtime = dtime-(times["m"]*60*1000)
            times["s"] = Math.floor(dtime/1000)
            times["ms"] = dtime % 1000
        else
            return (ts[0] * 3600000) + (ts[1] * 60000) + (ts2[0] * 1000) + (ts2[1] * 10) if !is_split
            times["h"] = ts[0] * 1
            times["m"] = ts[1] * 1
            times["s"] = ts2[0] * 1
            times["ms"] = ts2[1] * 1
        return times

    $('#update-btn').click ->
        data = get_forms()
        $('#words-text-box').val(data.words_list_text)
        $('form').submit()

    timer_btn.click ->
        if timer_mode == 0
            timer_input.removeAttr('disabled')
            $('.timer-set').removeAttr('disabled')
            timer_mode = 1
        else
            timer_input.attr('disabled', "")
            $('.timer-set').attr('disabled', "")
            timer_mode = 0

    # check tag num
    input_tag_form.change ->
        # TOOD: loading img
        tag_check_box.html '---'
        if (v = input_tag_form.val()) == ""
            return false
        data =
            tags_text: v
        url = "./make/tag_check/"
        if location.href.indexOf('update') != -1
            url = "../make/tag_check/"
        $.ajax(
            type: "POST",
            url: url
            data: data,
            success: (res) ->
                console.log res
                nums = res.split(',')
                tags = v.split(',')
                tag_check_box.html ''
                for i in [0...nums.length]
                    $tag = generate_tag_check_span(tags[i], nums[i])
                    tag_check_box.append $tag
            error: ->
                console.log 'result post error'
        )
        return false

    generate_tag_check_span = (tag_text, num) ->
        $badge = $('<span/>').addClass('badge').html(num)
        return $('<span/>').addClass('tag').html(tag_text).append($badge)

    turn_off = ($e)->
        $('.judge').stop()
        $('.judge').hide()
        $e.fadeIn(100).fadeOut(100).fadeIn(300).fadeOut(300)

    $('#favorite-btn').click ->
        favorite(1)
        $(@).addClass('hidden')
        $('#unfavorite-btn').removeClass('hidden')

    $('#unfavorite-btn').click ->
        favorite(0)
        $(@).addClass('hidden')
        $('#favorite-btn').removeClass('hidden')

    favorite = (is_regist)->
        data =
            is_regist : is_regist
        $.ajax(
            type: "POST",
            url: "./favorite/" + game_id
            data: data,
            success: (res) ->
                console.log res
            error: ->
                console.log 'result post error'
        )

    $('[data-toggle=tooltip]').tooltip()

    $('input[type=text]').on("keypress", (e) ->
        if e.which == 13
            id = $(@).attr('id')
            if (id == "input-search")
                return true
            if (id == "input_game_name")
                return
            return false
    )

    $('a[data-func=end]').click ->
        if game_flag != 0
            game_end()

