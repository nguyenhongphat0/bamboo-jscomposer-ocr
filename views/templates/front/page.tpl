
{extends file='page.tpl'}

{block name='page_content_container'}
  <section id="content" class="page-content page-cms page-cms-0">

    {block name='cms_content'}
      {$content nofilter}
    {/block}

  </section>
{/block}
