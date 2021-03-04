<template>
    <div>
        <div class="flex flex-shrink-0 p-4 pb-0">
            <a href="#" class="flex-shrink-0 group block">
                <div class="flex items-center">
                    <div>
                        <img class="inline-block h-10 w-10 rounded-full" :src="$page.props.user.profile_photo_path"
                             alt="">
                    </div>
                    <div class="ml-2">
                        <p class="text-base leading-6 font-medium dark:text-white">
                            {{ $page.props.user.name }}
                            <span
                                class="text-sm leading-5 font-medium text-gray-400 group-hover:text-gray-300 transition ease-in-out duration-150">
                            @{{ $page.props.user.username }} . {{ tweet.created_at }}
                          </span>
                        </p>
                    </div>
                </div>
            </a>
        </div>
        <div class="pl-16">
            <div v-if="tweet.in_reply_to_status_id">
                In reply to {{ tweet.in_reply_to_status_id }}
            </div>
            <div v-if="tweet.retweeted">
                Retweeted
            </div>
            <p class="text-base width-auto dark:text-white flex-shrink" v-html="text"/>
            <div class="flex">
                <div class="w-full">

                    <div class="flex items-center">
                        <div class="flex-1 text-center py-2 m-2">
                            <a href="#"
                               class="w-12 mt-1 group flex items-center text-gray-500 px-3 py-2 text-base leading-6 font-medium rounded-full hover:bg-blue-800 hover:text-blue-300">
                                <svg class="text-center h-7 w-6" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </a>
                            ({{ tweet.retweet_count }})
                        </div>

                        <div class="flex-1 text-center py-2 m-2">
                            <a href="#"
                               class="w-12 mt-1 group flex items-center text-gray-500 px-3 py-2 text-base leading-6 font-medium rounded-full hover:bg-blue-800 hover:text-blue-300">
                                <svg class="text-center h-7 w-6" fill="none" stroke-linecap="round"
                                     stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </a>
                            ({{ tweet.favorite_count }})
                        </div>

                    </div>
                </div>


            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: {
        tweet: Object
    },
    methods: {
        applyMentions(text) {
            const mentions = this.tweet.entities.user_mentions;
            mentions.forEach((mention, index) => {
                text = text.replace("@" + mention.screen_name, "<a href='https://twitter.com/" + mention.screen_name + "' class='link' target='_blank'>@" + mention.screen_name + "</a>");
            });
            return text;
        },
        applyUrls(text) {
            const urls = this.tweet.entities.urls;
            urls.forEach((url, index) => {
                text = text.replace(url.url, "<a href='" + url.expanded_url + "' class='link' target='_blank'>" + url.display_url + "</a>");
            });
            return text;
        },
        applyMedia(text) {
            if (typeof this.tweet.entities.media !== 'undefined') {
                let medias = this.tweet.entities.media;
                medias = medias.concat(this.tweet.extended_entities.media)
                let images = []
                let gifs = []
                let urls = []
                medias.forEach((media, index) => {
                    if (media.type === 'photo') {
                        if(!media.media_url_https.includes("tweet_video_thumb")) {
                            images.push(media.media_url_https);
                        }
                    }
                    if (media.type === 'animated_gif' && typeof media.video_info.variants[0].url !== 'undefined') {
                        gifs.push(media.video_info.variants[0].url);
                    }
                    urls.push(media.url)
                });
                images = images.filter(function(item, pos) {
                    return images.indexOf(item) == pos;
                })
                text += "<div class='images-container'>";
                images.forEach((image, index) => {
                    text += "<img src='" + image + "'  alt=''/>"
                });
                text += "</div>";

                text += "<div class='gifs-container'>";
                gifs.forEach((gif, index) => {
                    text += "<video width='320' height='240' controls>" +
                        " <source src='"+gif+"' type='video/mp4'></video>"
                });
                text += "</div>";

                urls.forEach((url, index) => {
                    text = text.replace(url, '');
                });
            }
            return text;
        },
        applyReplyLinks(text) {
            text = text.replace("@" + this.tweet.in_reply_to_screen_name, "<a href='https://twitter.com/" + this.tweet.in_reply_to_screen_name + "' class='link' target='_blank'>@" + this.tweet.in_reply_to_screen_name + "</a>");
            return text;
        }
    },
    computed: {
        text() {
            let text = this.tweet.text;
            text = this.applyMentions(text)
            text = this.applyUrls(text)
            text = this.applyMedia(text)
            text = this.applyReplyLinks(text)

            return text;
        }
    }
}
</script>
